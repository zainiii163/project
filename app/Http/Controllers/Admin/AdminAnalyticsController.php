<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_enrollments' => DB::table('course_user')->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_price'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
        ];

        // Revenue Chart Data (Last 12 Months)
        $revenueData = Order::where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Enrollment Chart Data (Last 12 Months)
        $enrollmentData = DB::table('course_user')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as enrollments')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top Courses by Enrollment
        $topCourses = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->limit(10)
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'revenueData',
            'enrollmentData',
            'topCourses',
            'recentOrders'
        ));
    }

    public function courses()
    {
        $courses = Course::with(['teacher', 'category'])
            ->withCount(['students', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(20);

        return view('admin.analytics.courses', compact('courses'));
    }

    public function revenue()
    {
        $revenueData = Order::where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue, COUNT(*) as orders')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $dailyRevenue = Order::where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as orders')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.revenue', compact('revenueData', 'dailyRevenue'));
    }

    public function users()
    {
        $userStats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        $registrationData = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recentUsers = User::latest()->limit(20)->get();

        return view('admin.analytics.users', compact('userStats', 'registrationData', 'recentUsers'));
    }

    public function generateReport(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $validated = $request->validate([
            'report_type' => 'required|in:enrollments,revenue,users,courses,quizzes',
            'format' => 'required|in:csv,pdf,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
        ]);

        $dateFrom = $validated['date_from'] ?? now()->subMonths(1);
        $dateTo = $validated['date_to'] ?? now();

        $data = $this->prepareReportData($validated['report_type'], $dateFrom, $dateTo);

        if ($validated['format'] === 'csv') {
            return $this->exportCsv($data, $validated['report_type']);
        } elseif ($validated['format'] === 'excel') {
            return $this->exportExcel($data, $validated['report_type']);
        } else {
            return $this->exportPdf($data, $validated['report_type']);
        }
    }

    public function kpis()
    {
        // Key Performance Indicators
        $kpis = [
            'students' => [
                'total' => User::where('role', 'student')->count(),
                'active' => User::where('role', 'student')->where('status', 'active')->count(),
                'new_this_month' => User::where('role', 'student')->whereMonth('created_at', now()->month)->count(),
                'completion_rate' => $this->calculateStudentCompletionRate(),
            ],
            'teachers' => [
                'total' => User::where('role', 'teacher')->count(),
                'active' => User::where('role', 'teacher')->where('status', 'active')->count(),
                'new_this_month' => User::where('role', 'teacher')->whereMonth('created_at', now()->month)->count(),
                'avg_rating' => $this->calculateAverageTeacherRating(),
            ],
            'courses' => [
                'total' => Course::count(),
                'published' => Course::where('status', 'published')->count(),
                'new_this_month' => Course::whereMonth('created_at', now()->month)->count(),
                'avg_enrollments' => round(Course::withCount('students')->get()->avg('students_count') ?? 0, 2),
            ],
            'revenue' => [
                'total' => Order::where('status', 'completed')->sum('total_price') ?? 0,
                'this_month' => Order::where('status', 'completed')
                    ->whereMonth('order_date', now()->month)
                    ->sum('total_price') ?? 0,
                'growth_rate' => $this->calculateRevenueGrowthRate(),
            ],
        ];

        return view('admin.analytics.kpis', compact('kpis'));
    }

    public function quizStats()
    {
        $quizStats = DB::table('quizzes')
            ->join('courses', 'quizzes.course_id', '=', 'courses.id')
            ->leftJoin('attempts', 'quizzes.id', '=', 'attempts.quiz_id')
            ->select(
                'quizzes.id',
                'quizzes.title',
                'courses.title as course_title',
                DB::raw('COUNT(DISTINCT attempts.id) as total_attempts'),
                DB::raw('AVG(attempts.score) as avg_score'),
                DB::raw('MAX(attempts.score) as max_score'),
                DB::raw('MIN(attempts.score) as min_score')
            )
            ->groupBy('quizzes.id', 'quizzes.title', 'courses.title')
            ->orderByDesc('total_attempts')
            ->paginate(20);

        return view('admin.analytics.quiz-stats', compact('quizStats'));
    }

    public function aiInsights()
    {
        // AI-assisted insights (placeholder - implement with actual AI service)
        $insights = [
            'student_engagement' => $this->analyzeStudentEngagement(),
            'dropout_prediction' => $this->predictDropouts(),
            'revenue_forecast' => $this->forecastRevenue(),
            'course_recommendations' => $this->recommendCourses(),
        ];

        return view('admin.analytics.ai-insights', compact('insights'));
    }

    private function prepareReportData($type, $dateFrom, $dateTo)
    {
        switch ($type) {
            case 'enrollments':
                return DB::table('course_user')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->join('courses', 'course_user.course_id', '=', 'courses.id')
                    ->join('users', 'course_user.user_id', '=', 'users.id')
                    ->select('courses.title as course', 'users.name as student', 'course_user.created_at as enrolled_at')
                    ->get();
            
            case 'revenue':
                return Order::whereBetween('order_date', [$dateFrom, $dateTo])
                    ->where('status', 'completed')
                    ->with(['user', 'items.course'])
                    ->get();
            
            case 'users':
                return User::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->get();
            
            case 'courses':
                return Course::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->with(['teacher', 'category'])
                    ->get();
            
            case 'quizzes':
                return DB::table('quizzes')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->join('courses', 'quizzes.course_id', '=', 'courses.id')
                    ->select('quizzes.*', 'courses.title as course_title')
                    ->get();
            
            default:
                return collect();
        }
    }

    private function exportCsv($data, $type)
    {
        $filename = $type . '_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            // Write headers based on type
            if ($type === 'enrollments') {
                fputcsv($file, ['Course', 'Student', 'Enrolled At']);
                foreach ($data as $row) {
                    fputcsv($file, [$row->course, $row->student, $row->enrolled_at]);
                }
            } elseif ($type === 'revenue') {
                fputcsv($file, ['Order ID', 'Date', 'Student', 'Amount', 'Status']);
                foreach ($data as $order) {
                    fputcsv($file, [$order->id, $order->order_date, $order->user->name, $order->total_price, $order->status]);
                }
            }
            // Add more cases as needed
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function exportExcel($data, $type)
    {
        // Implement Excel export using Maatwebsite\Excel
        // This is a placeholder
        return back()->with('info', 'Excel export feature coming soon');
    }

    private function exportPdf($data, $type)
    {
        // Implement PDF export using DomPDF or similar
        // This is a placeholder
        return back()->with('info', 'PDF export feature coming soon');
    }

    private function calculateStudentCompletionRate()
    {
        $totalEnrollments = DB::table('course_user')->count();
        $completedEnrollments = DB::table('course_user')->whereNotNull('completed_at')->count();
        
        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;
    }

    private function calculateAverageTeacherRating()
    {
        return round(DB::table('reviews')
            ->join('courses', 'reviews.course_id', '=', 'courses.id')
            ->where('courses.teacher_id', '!=', null)
            ->avg('reviews.rating') ?? 0, 2);
    }

    private function calculateRevenueGrowthRate()
    {
        $thisMonth = Order::where('status', 'completed')
            ->whereMonth('order_date', now()->month)
            ->sum('total_price') ?? 0;
        
        $lastMonth = Order::where('status', 'completed')
            ->whereMonth('order_date', now()->subMonth()->month)
            ->sum('total_price') ?? 0;
        
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    private function analyzeStudentEngagement()
    {
        // Placeholder for AI analysis
        return [
            'highly_engaged' => User::where('role', 'student')->count() * 0.3,
            'moderately_engaged' => User::where('role', 'student')->count() * 0.5,
            'low_engagement' => User::where('role', 'student')->count() * 0.2,
        ];
    }

    private function predictDropouts()
    {
        // Placeholder for dropout prediction
        return [
            'at_risk_students' => [],
            'risk_factors' => ['inactivity', 'low_progress', 'no_quiz_attempts'],
        ];
    }

    private function forecastRevenue()
    {
        // Simple linear forecast (replace with actual ML model)
        $last3Months = Order::where('status', 'completed')
            ->where('order_date', '>=', now()->subMonths(3))
            ->sum('total_price') ?? 0;
        
        return [
            'next_month' => $last3Months / 3,
            'next_quarter' => $last3Months,
        ];
    }

    private function recommendCourses()
    {
        // Placeholder for course recommendations
        return Course::withCount('students')
            ->orderByDesc('students_count')
            ->limit(5)
            ->get();
    }
}

