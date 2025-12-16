<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attempt;
use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // Basic Statistics
        $totalUsers = User::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $totalEnrollments = DB::table('course_user')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price') ?? 0;
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Today's Statistics
        $today = now()->startOfDay();
        $todayVisitors = User::where('created_at', '>=', $today)->count();
        $todayProductsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.order_date', '>=', $today)
            ->sum('order_items.quantity');
        $todayProductsSold = $todayProductsSold ?? 0;
        $todayOrders = Order::where('order_date', '>=', $today)->count();
        $todayRevenue = Order::where('status', 'completed')
            ->where('order_date', '>=', $today)
            ->sum('total_price');
        $todayRevenue = $todayRevenue ?? 0;

        // Calculate percentages (for progress bars)
        $uniqueVisitorPercent = $totalUsers > 0 ? min(100, ($todayVisitors / $totalUsers) * 100) : 0;
        $productSoldPercent = $totalEnrollments > 0 ? min(100, ($todayProductsSold / $totalEnrollments) * 100) : 0;
        $orderReceivedPercent = $totalCourses > 0 ? min(100, ($todayOrders / $totalCourses) * 100) : 0;
        $revenuePercent = $totalRevenue > 0 ? min(100, ($todayRevenue / $totalRevenue) * 100) : 0;

        $stats = [
            'total_users' => $totalUsers,
            'total_teachers' => $totalTeachers,
            'total_students' => $totalStudents,
            'total_courses' => $totalCourses,
            'published_courses' => $publishedCourses,
            'total_enrollments' => $totalEnrollments,
            'total_revenue' => $totalRevenue,
            'pending_orders' => $pendingOrders,
            // Today's stats
            'today_visitors' => $todayVisitors,
            'today_products_sold' => $todayProductsSold,
            'today_orders' => $todayOrders,
            'today_revenue' => $todayRevenue,
            // Percentages
            'visitor_percent' => round($uniqueVisitorPercent),
            'product_percent' => round($productSoldPercent),
            'order_percent' => round($orderReceivedPercent),
            'revenue_percent' => round($revenuePercent),
        ];

        // Revenue Statistics (Last 12 months)
        $revenueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $revenueData[] = [
                'month' => $month->format('M'),
                'revenue' => Order::where('status', 'completed')
                    ->whereBetween('order_date', [$monthStart, $monthEnd])
                    ->sum('total_price') ?? 0,
                'views' => Course::whereBetween('created_at', [$monthStart, $monthEnd])->count() * 100, // Simulated
                'support' => User::whereBetween('created_at', [$monthStart, $monthEnd])->count() * 10, // Simulated
            ];
        }

        // Market Trends (Donut Chart Data)
        $newCustomers = User::where('created_at', '>=', now()->subDays(30))->count();
        $revenue = $totalRevenue;
        $productsSold = $totalEnrollments;
        $profit = $totalRevenue * 0.3; // Assuming 30% profit margin

        $marketTrends = [
            'new_customer' => $newCustomers,
            'revenue' => $revenue,
            'product_sold' => $productsSold,
            'profit' => $profit,
        ];

        // Recent Transactions (Orders with items)
        $recentTransactions = Order::with(['user', 'items.course'])
            ->latest()
            ->take(10)
            ->get();

        // Daily Sale Report (Last 7 days)
        $dailySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $orders = Order::with('user')->whereBetween('order_date', [$dayStart, $dayEnd])->get();
            $firstOrder = $orders->first();
            $dailySales[] = [
                'date' => $date->format('M d'),
                'client' => $firstOrder && $firstOrder->user ? $firstOrder->user->name : 'No Sales',
                'detail' => $orders->count() . ($orders->count() == 1 ? ' order' : ' orders'),
                'payment' => $orders->sum('total_price') ?? 0,
            ];
        }

        $recent_courses = Course::with('teacher')->latest()->take(10)->get();
        $recent_orders = Order::with('user')->latest()->take(10)->get();

        return view('dashboard.admin', compact(
            'stats', 
            'recent_courses', 
            'recent_orders',
            'revenueData',
            'marketTrends',
            'recentTransactions',
            'dailySales'
        ));
    }

    public function teacher()
    {
        $teacher = auth()->user();
        $teacherCourses = $teacher->taughtCourses();
        $courseIds = $teacherCourses->pluck('id');
        
        $stats = [
            'total_courses' => $teacherCourses->count(),
            'published_courses' => $teacherCourses->where('status', 'published')->count(),
            'total_students' => $teacherCourses->withCount('students')->get()->sum('students_count'),
            'total_enrollments' => DB::table('course_user')
                ->whereIn('course_id', $courseIds)
                ->count(),
        ];

        // Calculate revenue from teacher's courses
        $revenue = Order::whereHas('items', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })
        ->where('status', 'completed')
        ->sum('total_price') ?? 0;

        // Recent enrollments (last 30 days)
        $recentEnrollments = DB::table('course_user')
            ->whereIn('course_id', $courseIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Course performance data (last 6 months)
        $performanceData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $enrollments = DB::table('course_user')
                ->whereIn('course_id', $courseIds)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $performanceData[] = [
                'month' => $month->format('M'),
                'enrollments' => $enrollments,
            ];
        }

        // Average course rating
        $avgRating = Review::whereIn('course_id', $courseIds)
            ->where('approved', true)
            ->avg('rating') ?? 0;

        $courses = $teacherCourses->with('category')->latest()->take(10)->get();

        return view('dashboard.teacher', compact(
            'stats', 
            'courses', 
            'revenue', 
            'recentEnrollments', 
            'performanceData',
            'avgRating'
        ));
    }

    public function student()
    {
        $student = auth()->user();
        
        $stats = [
            'enrolled_courses' => $student->courses()->count(),
            'completed_courses' => $student->courses()->wherePivot('completed_at', '!=', null)->count(),
            'certificates' => $student->certificates()->count(),
            'in_progress' => $student->courses()->wherePivot('completed_at', null)->count(),
        ];

        // Learning progress data (last 6 months)
        $progressData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $completedLessons = LessonProgress::where('user_id', $student->id)
                ->where('completed', true)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $progressData[] = [
                'month' => $month->format('M'),
                'lessons_completed' => $completedLessons,
            ];
        }

        // Recent announcements
        $recent_announcements = Announcement::whereHas('recipients', function($q) use ($student) {
            $q->where('users.id', $student->id);
        })
        ->orWhere('scope', 'all')
        ->with('course')
        ->latest()
        ->take(5)
        ->get();

        // Upcoming assignments (due in next 7 days)
        $upcoming_assignments = Assignment::where('student_id', $student->id)
            ->whereNull('submitted_at')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->with('course')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Study statistics
        $total_lessons_completed = LessonProgress::where('user_id', $student->id)
            ->where('completed', true)
            ->count();
        
        $total_quizzes_taken = Attempt::where('user_id', $student->id)
            ->count();

        $enrolled_courses = $student->courses()->with('teacher', 'category')->latest()->take(10)->get();
        $recent_certificates = $student->certificates()->with('course')->latest()->take(5)->get();

        return view('dashboard.student', compact(
            'stats', 
            'enrolled_courses', 
            'recent_certificates',
            'progressData',
            'recent_announcements',
            'upcoming_assignments',
            'total_lessons_completed',
            'total_quizzes_taken'
        ));
    }
}

