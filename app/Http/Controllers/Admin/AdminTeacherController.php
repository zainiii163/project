<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\Review;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTeacherController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::where('role', 'teacher')->withCount(['taughtCourses', 'reviews']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $teachers = $query->latest()->paginate(20);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function show(User $teacher)
    {
        $this->authorize('view', $teacher);
        
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        // Load teacher data
        $teacher->load(['taughtCourses.category', 'reviews']);

        // Performance metrics
        $metrics = [
            'total_courses' => $teacher->taughtCourses()->count(),
            'published_courses' => $teacher->taughtCourses()->where('status', 'published')->count(),
            'total_students' => DB::table('course_user')
                ->whereIn('course_id', $teacher->taughtCourses()->pluck('id'))
                ->distinct('user_id')
                ->count('user_id'),
            'total_enrollments' => DB::table('course_user')
                ->whereIn('course_id', $teacher->taughtCourses()->pluck('id'))
                ->count(),
            'average_rating' => $teacher->taughtCourses()
                ->withAvg('reviews', 'rating')
                ->get()
                ->avg('reviews_avg_rating') ?? 0,
            'total_reviews' => Review::whereHas('course', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->count(),
            'completion_rate' => $this->calculateCompletionRate($teacher),
            'engagement_score' => $this->calculateEngagementScore($teacher),
        ];

        // Revenue metrics
        $revenue = $this->calculateTeacherRevenue($teacher);
        
        // Course performance
        $coursePerformance = $teacher->taughtCourses()
            ->withCount(['students', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->get()
            ->map(function($course) {
                return [
                    'course' => $course,
                    'enrollments' => $course->students_count,
                    'rating' => round($course->reviews_avg_rating ?? 0, 2),
                    'reviews_count' => $course->reviews_count,
                ];
            });

        return view('admin.teachers.show', compact('teacher', 'metrics', 'revenue', 'coursePerformance'));
    }

    public function approve(User $teacher)
    {
        $this->authorize('update', $teacher);
        
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'Only teacher accounts can be approved.');
        }

        $teacher->update(['status' => 'active']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'teacher_approved',
            'model_type' => User::class,
            'model_id' => $teacher->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Teacher account approved successfully!');
    }

    public function suspend(User $teacher)
    {
        $this->authorize('update', $teacher);
        
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'Only teacher accounts can be suspended.');
        }

        $teacher->update(['status' => 'suspended']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'teacher_suspended',
            'model_type' => User::class,
            'model_id' => $teacher->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Teacher account suspended successfully!');
    }

    public function payouts(User $teacher)
    {
        $this->authorize('view', $teacher);
        
        // Calculate teacher earnings
        $earnings = $this->calculateTeacherEarnings($teacher);
        
        // Get payout history (if you have a payouts table)
        // $payouts = Payout::where('teacher_id', $teacher->id)->latest()->get();
        
        return view('admin.teachers.payouts', compact('teacher', 'earnings'));
    }

    public function managePayout(Request $request, User $teacher)
    {
        $this->authorize('update', $teacher);
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Process payout (implement based on your payment system)
        // This is a placeholder - implement actual payout logic
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'payout_processed',
            'model_type' => User::class,
            'model_id' => $teacher->id,
            'new_values' => $validated,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Payout processed successfully!');
    }

    public function assignTask(Request $request, User $teacher)
    {
        $this->authorize('update', $teacher);
        
        $validated = $request->validate([
            'task_description' => 'required|string|max:2000',
            'due_date' => 'nullable|date|after:today',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        // Create task (implement if you have a tasks table)
        // Task::create([...]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'task_assigned',
            'model_type' => User::class,
            'model_id' => $teacher->id,
            'new_values' => $validated,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Task assigned successfully!');
    }

    private function calculateCompletionRate(User $teacher)
    {
        $totalEnrollments = DB::table('course_user')
            ->whereIn('course_id', $teacher->taughtCourses()->pluck('id'))
            ->count();
            
        $completedEnrollments = DB::table('course_user')
            ->whereIn('course_id', $teacher->taughtCourses()->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();
        
        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;
    }

    private function calculateEngagementScore(User $teacher)
    {
        // Calculate based on student interactions, quiz attempts, discussion participation, etc.
        $courses = $teacher->taughtCourses()->pluck('id');
        
        $quizAttempts = DB::table('attempts')
            ->whereIn('quiz_id', function($q) use ($courses) {
                $q->select('id')->from('quizzes')->whereIn('course_id', $courses);
            })
            ->count();
        
        $discussions = DB::table('discussions')
            ->whereIn('course_id', $courses)
            ->count();
        
        // Simple scoring algorithm (adjust as needed)
        $score = min(100, ($quizAttempts * 0.1) + ($discussions * 0.5));
        
        return round($score, 2);
    }

    private function calculateTeacherRevenue(User $teacher)
    {
        $courseIds = $teacher->taughtCourses()->pluck('id');
        
        $totalRevenue = Order::whereHas('items', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })
        ->where('status', 'completed')
        ->sum('total_price') ?? 0;
        
        // Assuming platform takes 30% commission
        $platformCommission = $totalRevenue * 0.30;
        $teacherEarnings = $totalRevenue * 0.70;
        
        return [
            'total_revenue' => $totalRevenue,
            'platform_commission' => $platformCommission,
            'teacher_earnings' => $teacherEarnings,
            'pending_payouts' => $teacherEarnings, // Adjust based on actual payout logic
        ];
    }

    private function calculateTeacherEarnings(User $teacher)
    {
        return $this->calculateTeacherRevenue($teacher);
    }
}

