<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Order;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportingController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function enrollments(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonths(1)->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $enrollments = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->whereBetween('course_user.created_at', [$dateFrom, $dateTo])
            ->select(
                'courses.title as course_title',
                'users.name as student_name',
                'users.email as student_email',
                'course_user.enrolled_at',
                'course_user.progress',
                'course_user.completed_at'
            )
            ->orderBy('course_user.created_at', 'desc')
            ->get();

        $summary = [
            'total' => $enrollments->count(),
            'completed' => $enrollments->whereNotNull('completed_at')->count(),
            'in_progress' => $enrollments->whereNull('completed_at')->count(),
            'avg_progress' => round($enrollments->avg('progress') ?? 0, 2),
        ];

        if ($request->wantsJson() || $request->input('format') === 'json') {
            return response()->json([
                'summary' => $summary,
                'data' => $enrollments,
            ]);
        }

        return view('admin.reports.enrollments', compact('enrollments', 'summary', 'dateFrom', 'dateTo'));
    }

    public function revenue(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonths(1)->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $orders = Order::with(['user', 'items.course'])
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->get();

        $revenueByCourse = DB::table('order_items')
            ->join('courses', 'order_items.course_id', '=', 'courses.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.order_date', [$dateFrom, $dateTo])
            ->select(
                'courses.id',
                'courses.title',
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_sales')
            )
            ->groupBy('courses.id', 'courses.title')
            ->orderByDesc('total_revenue')
            ->get();

        $revenueByDate = Order::where('status', 'completed')
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->selectRaw('DATE(order_date) as date, SUM(total_price) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $summary = [
            'total_revenue' => $orders->sum('total_price'),
            'total_orders' => $orders->count(),
            'avg_order_value' => round($orders->avg('total_price') ?? 0, 2),
            'unique_customers' => $orders->pluck('user_id')->unique()->count(),
        ];

        if ($request->wantsJson() || $request->input('format') === 'json') {
            return response()->json([
                'summary' => $summary,
                'revenue_by_course' => $revenueByCourse,
                'revenue_by_date' => $revenueByDate,
            ]);
        }

        return view('admin.reports.revenue', compact('orders', 'revenueByCourse', 'revenueByDate', 'summary', 'dateFrom', 'dateTo'));
    }

    public function users(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonths(1)->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $users = User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->withCount(['courses', 'certificates', 'orders'])
            ->get();

        $usersByRole = User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();

        $usersByDate = User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $summary = [
            'total' => $users->count(),
            'students' => $users->where('role', 'student')->count(),
            'teachers' => $users->where('role', 'teacher')->count(),
            'active' => $users->where('status', 'active')->count(),
        ];

        if ($request->wantsJson() || $request->input('format') === 'json') {
            return response()->json([
                'summary' => $summary,
                'users_by_role' => $usersByRole,
                'users_by_date' => $usersByDate,
                'data' => $users,
            ]);
        }

        return view('admin.reports.users', compact('users', 'usersByRole', 'usersByDate', 'summary', 'dateFrom', 'dateTo'));
    }

    public function courses(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subMonths(1)->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $courses = Course::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['teacher', 'category'])
            ->withCount(['students', 'reviews', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->get();

        $coursesByStatus = Course::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $topCourses = Course::whereBetween('created_at', [$dateFrom, $dateTo])
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(10)
            ->get();

        $summary = [
            'total' => $courses->count(),
            'published' => $courses->where('status', 'published')->count(),
            'draft' => $courses->where('status', 'draft')->count(),
            'avg_enrollments' => round($courses->avg('students_count') ?? 0, 2),
            'avg_rating' => round($courses->avg('reviews_avg_rating') ?? 0, 2),
        ];

        if ($request->wantsJson() || $request->input('format') === 'json') {
            return response()->json([
                'summary' => $summary,
                'courses_by_status' => $coursesByStatus,
                'top_courses' => $topCourses,
                'data' => $courses,
            ]);
        }

        return view('admin.reports.courses', compact('courses', 'coursesByStatus', 'topCourses', 'summary', 'dateFrom', 'dateTo'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:enrollments,revenue,users,courses',
            'format' => 'required|in:csv,pdf,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
        ]);

        $dateFrom = $validated['date_from'] ?? now()->subMonths(1)->toDateString();
        $dateTo = $validated['date_to'] ?? now()->toDateString();

        switch ($validated['report_type']) {
            case 'enrollments':
                return $this->exportEnrollments($dateFrom, $dateTo, $validated['format']);
            case 'revenue':
                return $this->exportRevenue($dateFrom, $dateTo, $validated['format']);
            case 'users':
                return $this->exportUsers($dateFrom, $dateTo, $validated['format']);
            case 'courses':
                return $this->exportCourses($dateFrom, $dateTo, $validated['format']);
        }
    }

    private function exportEnrollments($dateFrom, $dateTo, $format)
    {
        $enrollments = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->whereBetween('course_user.created_at', [$dateFrom, $dateTo])
            ->select(
                'courses.title as course',
                'users.name as student',
                'users.email',
                'course_user.enrolled_at',
                'course_user.progress',
                'course_user.completed_at'
            )
            ->get();

        if ($format === 'csv') {
            return $this->exportCsv($enrollments, 'enrollments');
        }

        // PDF and Excel exports would go here
        return back()->with('info', 'PDF/Excel export coming soon');
    }

    private function exportRevenue($dateFrom, $dateTo, $format)
    {
        $orders = Order::with(['user', 'items.course'])
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->get();

        if ($format === 'csv') {
            $data = $orders->map(function($order) {
                return [
                    'order_id' => $order->id,
                    'date' => $order->order_date->format('Y-m-d'),
                    'customer' => $order->user->name,
                    'email' => $order->user->email,
                    'amount' => $order->total_price,
                    'status' => $order->status,
                ];
            });

            return $this->exportCsv($data, 'revenue');
        }

        return back()->with('info', 'PDF/Excel export coming soon');
    }

    private function exportUsers($dateFrom, $dateTo, $format)
    {
        $users = User::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        if ($format === 'csv') {
            $data = $users->map(function($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->exportCsv($data, 'users');
        }

        return back()->with('info', 'PDF/Excel export coming soon');
    }

    private function exportCourses($dateFrom, $dateTo, $format)
    {
        $courses = Course::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['teacher', 'category'])
            ->withCount('students')
            ->get();

        if ($format === 'csv') {
            $data = $courses->map(function($course) {
                return [
                    'title' => $course->title,
                    'teacher' => $course->teacher->name ?? 'N/A',
                    'category' => $course->category->name ?? 'N/A',
                    'price' => $course->price,
                    'status' => $course->status,
                    'enrollments' => $course->students_count,
                    'created_at' => $course->created_at->format('Y-m-d'),
                ];
            });

            return $this->exportCsv($data, 'courses');
        }

        return back()->with('info', 'PDF/Excel export coming soon');
    }

    private function exportCsv($data, $type)
    {
        $filename = $type . '_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                // Write headers
                fputcsv($file, array_keys((array)$data->first()));
                
                // Write data
                foreach ($data as $row) {
                    fputcsv($file, (array)$row);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

