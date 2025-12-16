<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::with(['user', 'items.course', 'transaction']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(20);

        // Summary statistics
        $stats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total_price') ?? 0,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'today_revenue' => Order::where('status', 'completed')
                ->whereDate('order_date', today())
                ->sum('total_price') ?? 0,
        ];

        return view('admin.payments.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items.course', 'transaction']);

        return view('admin.payments.show', compact('order'));
    }

    public function transactions(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $query = Transaction::with(['order.user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate(20);

        return view('admin.payments.transactions', compact('transactions'));
    }

    public function coupons()
    {
        $this->authorize('viewAny', Coupon::class);

        $coupons = Coupon::latest()->paginate(20);

        return view('admin.payments.coupons', compact('coupons'));
    }

    public function createCoupon()
    {
        $this->authorize('create', Coupon::class);

        return view('admin.payments.coupons.create');
    }

    public function storeCoupon(Request $request)
    {
        $this->authorize('create', Coupon::class);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        // Map usage_limit to max_uses for database
        $validated['max_uses'] = $validated['usage_limit'] ?? null;
        unset($validated['usage_limit']);

        Coupon::create($validated);

        return redirect()->route('admin.payments.coupons')
            ->with('success', 'Coupon created successfully!');
    }

    public function editCoupon(Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        return view('admin.payments.coupons.edit', compact('coupon'));
    }

    public function updateCoupon(Request $request, Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        // Map usage_limit to max_uses for database
        $validated['max_uses'] = $validated['usage_limit'] ?? null;
        unset($validated['usage_limit']);

        $coupon->update($validated);

        return redirect()->route('admin.payments.coupons')
            ->with('success', 'Coupon updated successfully!');
    }

    public function handleDispute(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'resolution' => 'required|in:refund,partial_refund,reject',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'required|string|max:1000',
        ]);

        if ($validated['resolution'] === 'refund') {
            // Process full refund
            $order->update(['status' => 'refunded']);
        } elseif ($validated['resolution'] === 'partial_refund') {
            // Process partial refund
            $order->update(['status' => 'partially_refunded']);
        } else {
            $order->update(['status' => 'dispute_rejected']);
        }

        return back()->with('success', 'Dispute handled successfully!');
    }

    public function processRefund(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'reason' => 'required|string|max:1000',
        ]);

        $refundAmount = $validated['amount'] ?? $order->total_price;

        // Process refund through payment gateway
        // This is a placeholder - implement actual refund logic

        $order->update(['status' => 'refunded']);

        return back()->with('success', 'Refund processed successfully!');
    }

    public function revenueReport(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $dateFrom = $request->get('date_from', now()->subMonths(1)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Overall revenue
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->sum('total_price') ?? 0;

        // Revenue by course
        $revenueByCourse = Order::where('status', 'completed')
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('courses', 'order_items.course_id', '=', 'courses.id')
            ->select('courses.id', 'courses.title', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'), DB::raw('COUNT(*) as sales'))
            ->groupBy('courses.id', 'courses.title')
            ->orderByDesc('revenue')
            ->get();

        // Revenue by teacher
        $revenueByTeacher = Order::where('status', 'completed')
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('courses', 'order_items.course_id', '=', 'courses.id')
            ->join('users', 'courses.teacher_id', '=', 'users.id')
            ->select('users.id', 'users.name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'), DB::raw('COUNT(*) as sales'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->get();

        // Daily revenue breakdown
        $dailyRevenue = [];
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        while ($startDate <= $endDate) {
            $dayRevenue = Order::where('status', 'completed')
                ->whereDate('order_date', $startDate->format('Y-m-d'))
                ->sum('total_price') ?? 0;

            $dailyRevenue[] = [
                'date' => $startDate->format('Y-m-d'),
                'revenue' => $dayRevenue,
            ];

            $startDate->addDay();
        }

        return view('admin.payments.revenue-report', compact(
            'totalRevenue',
            'revenueByCourse',
            'revenueByTeacher',
            'dailyRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

    public function exportRevenueReport(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $dateFrom = $request->get('date_from', now()->subMonths(1)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->with(['user', 'items.course'])
            ->get();

        $filename = 'revenue_report_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Date', 'Student', 'Course', 'Amount', 'Status']);

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    fputcsv($file, [
                        $order->id,
                        $order->order_date,
                        $order->user->name,
                        $item->course->title ?? 'N/A',
                        $item->price,
                        $order->status,
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function trackPaymentsByStudent(User $student)
    {
        $this->authorize('view', $student);

        $payments = $student->orders()
            ->with(['items.course', 'transaction'])
            ->latest()
            ->get();

        $totalSpent = $payments->where('status', 'completed')->sum('total_price');

        return view('admin.payments.student-payments', compact('student', 'payments', 'totalSpent'));
    }

    public function trackPaymentsByTeacher(User $teacher)
    {
        $this->authorize('view', $teacher);

        $courseIds = $teacher->taughtCourses()->pluck('id');

        $payments = Order::whereHas('items', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })
        ->with(['user', 'items.course', 'transaction'])
        ->latest()
        ->get();

        $totalEarnings = $payments->where('status', 'completed')->sum('total_price') * 0.70; // Assuming 70% commission

        return view('admin.payments.teacher-payments', compact('teacher', 'payments', 'totalEarnings'));
    }
}

