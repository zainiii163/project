<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\Attempt;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::where('role', 'student')->withCount(['courses', 'certificates', 'attempts']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->latest()->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    public function show(User $student)
    {
        $this->authorize('view', $student);
        
        if ($student->role !== 'student') {
            abort(404);
        }

        // Load student data
        $student->load([
            'courses' => function($q) {
                $q->withPivot('enrolled_at', 'progress', 'completed_at');
            },
            'certificates.course',
            'attempts.quiz.course',
            'orders.items.course',
            'reviews.course',
        ]);

        // Enrollment statistics
        $enrollmentStats = [
            'total_enrollments' => $student->courses()->count(),
            'completed_courses' => $student->courses()->wherePivot('completed_at', '!=', null)->count(),
            'in_progress' => $student->courses()->wherePivot('completed_at', null)->count(),
            'average_progress' => round($student->courses()->avg('course_user.progress') ?? 0, 2),
        ];

        // Quiz performance
        $quizPerformance = $student->attempts()
            ->with(['quiz.course'])
            ->select('quiz_id', DB::raw('AVG(score) as avg_score'), DB::raw('MAX(score) as max_score'), DB::raw('COUNT(*) as attempts'))
            ->groupBy('quiz_id')
            ->get();

        // Activity tracking
        $activity = [
            'last_login' => $student->last_login,
            'total_quiz_attempts' => $student->attempts()->count(),
            'certificates_earned' => $student->certificates()->count(),
            'reviews_submitted' => $student->reviews()->count(),
        ];

        // Payment history
        $payments = $student->orders()
            ->with(['items.course', 'transaction'])
            ->latest()
            ->get();

        return view('admin.students.show', compact('student', 'enrollmentStats', 'quizPerformance', 'activity', 'payments'));
    }

    public function monitorActivity(User $student)
    {
        $this->authorize('view', $student);
        
        // Get recent activity logs
        $activityLogs = AuditLog::where('user_id', $student->id)
            ->orWhere(function($q) use ($student) {
                $q->where('model_type', User::class)
                  ->where('model_id', $student->id);
            })
            ->with('user')
            ->latest()
            ->paginate(50);
        
        // Get course progress details
        $courseProgress = $student->courses()
            ->with(['teacher', 'category'])
            ->withPivot('enrolled_at', 'progress', 'completed_at')
            ->get()
            ->map(function($course) use ($student) {
                $completedLessons = DB::table('lesson_progress')
                    ->where('user_id', $student->id)
                    ->whereHas('lesson', function($q) use ($course) {
                        $q->where('course_id', $course->id);
                    })
                    ->where('completed', true)
                    ->count();
                
                $totalLessons = $course->lessons()->count();
                
                return [
                    'course' => $course,
                    'progress' => $course->pivot->progress ?? 0,
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                    'enrolled_at' => $course->pivot->enrolled_at,
                    'completed_at' => $course->pivot->completed_at,
                ];
            });
        
        return view('admin.students.activity', compact('student', 'activityLogs', 'courseProgress'));
    }

    public function handleComplaint(Request $request, User $student)
    {
        $this->authorize('update', $student);
        
        $validated = $request->validate([
            'complaint_type' => 'required|in:refund,technical,content,other',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Create complaint record (implement if you have a complaints table)
        // Complaint::create([...]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'complaint_handled',
            'model_type' => User::class,
            'model_id' => $student->id,
            'new_values' => $validated,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Complaint handled successfully!');
    }

    public function processRefund(Request $request, User $student)
    {
        $this->authorize('update', $student);
        
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        
        if ($order->user_id !== $student->id) {
            return back()->with('error', 'Order does not belong to this student.');
        }

        // Process refund (implement based on your payment gateway)
        // This is a placeholder - implement actual refund logic
        
        $order->update(['status' => 'refunded']);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'refund_processed',
            'model_type' => Order::class,
            'model_id' => $order->id,
            'new_values' => $validated,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Refund processed successfully!');
    }

    public function viewFeedback(User $student)
    {
        $this->authorize('view', $student);
        
        $reviews = $student->reviews()
            ->with('course')
            ->latest()
            ->get();
        
        // Get feedback from discussions, assignments, etc.
        $feedback = [
            'reviews' => $reviews,
            'assignment_feedback' => [], // Implement if you have assignment feedback
        ];
        
        return view('admin.students.feedback', compact('student', 'feedback'));
    }

    public function suspend(User $student)
    {
        $this->authorize('update', $student);
        
        if ($student->role !== 'student') {
            return back()->with('error', 'Only student accounts can be suspended.');
        }

        $student->update(['status' => 'suspended']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'student_suspended',
            'model_type' => User::class,
            'model_id' => $student->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Student account suspended successfully!');
    }

    public function activate(User $student)
    {
        $this->authorize('update', $student);
        
        $student->update(['status' => 'active']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'student_activated',
            'model_type' => User::class,
            'model_id' => $student->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Student account activated successfully!');
    }
}

