<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Payout::with(['teacher', 'commissions']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $payouts = $query->latest()->paginate(20);

        $teachers = User::where('role', 'teacher')->get();

        $stats = [
            'pending' => Payout::where('status', 'pending')->sum('amount'),
            'processing' => Payout::where('status', 'processing')->sum('amount'),
            'completed' => Payout::where('status', 'completed')->sum('amount'),
            'failed' => Payout::where('status', 'failed')->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payouts', 'teachers', 'stats'));
    }

    public function create(Request $request)
    {
        $teachers = User::where('role', 'teacher')
            ->withSum('commissions', 'amount')
            ->get()
            ->filter(function($teacher) {
                return $teacher->commissions_sum_amount > 0;
            });

        $teacher = null;
        $pendingCommissions = collect();

        if ($request->has('teacher_id')) {
            $teacher = User::where('role', 'teacher')->findOrFail($request->teacher_id);
            $pendingCommissions = Commission::where('teacher_id', $teacher->id)
                ->where('status', 'pending')
                ->with(['order.user', 'course'])
                ->get();
        }

        return view('admin.payouts.create', compact('teachers', 'teacher', 'pendingCommissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,paypal,stripe,check',
            'payment_details' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get pending commissions for this teacher
        $pendingCommissions = Commission::where('teacher_id', $validated['teacher_id'])
            ->where('status', 'pending')
            ->get();

        $totalPending = $pendingCommissions->sum('amount');

        if ($validated['amount'] > $totalPending) {
            return back()->with('error', 'Payout amount cannot exceed pending commissions.');
        }

        $payout = Payout::create([
            'teacher_id' => $validated['teacher_id'],
            'amount' => $validated['amount'],
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'payment_details' => json_encode($validated['payment_details'] ?? []),
            'notes' => $validated['notes'],
        ]);

        // Mark commissions as paid
        $allocatedAmount = 0;
        foreach ($pendingCommissions as $commission) {
            if ($allocatedAmount >= $validated['amount']) {
                break;
            }

            $commissionAmount = min($commission->amount, $validated['amount'] - $allocatedAmount);
            $commission->update([
                'status' => 'paid',
                'payout_id' => $payout->id,
                'paid_at' => now(),
            ]);

            $allocatedAmount += $commissionAmount;
        }

        return redirect()->route('admin.payouts.index')
            ->with('success', 'Payout created successfully!');
    }

    public function show(Payout $payout)
    {
        $payout->load(['teacher', 'commissions.order', 'commissions.course']);

        return view('admin.payouts.show', compact('payout'));
    }

    public function process(Payout $payout)
    {
        $payout->update(['status' => 'processing']);

        // TODO: Integrate with payment gateway (PayPal, Stripe, etc.)
        // For now, just mark as completed after processing

        return back()->with('success', 'Payout is being processed.');
    }

    public function complete(Payout $payout)
    {
        $payout->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        // TODO: Send notification to teacher

        return back()->with('success', 'Payout marked as completed.');
    }

    public function fail(Payout $payout)
    {
        $payout->update(['status' => 'failed']);

        // Revert commissions back to pending
        $payout->commissions()->update([
            'status' => 'pending',
            'payout_id' => null,
            'paid_at' => null,
        ]);

        return back()->with('success', 'Payout marked as failed. Commissions reverted.');
    }

    public function teacherEarnings(User $teacher)
    {
        $commissions = Commission::where('teacher_id', $teacher->id)
            ->with(['order', 'course'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_earnings' => Commission::where('teacher_id', $teacher->id)
                ->where('status', 'paid')
                ->sum('amount'),
            'pending_earnings' => Commission::where('teacher_id', $teacher->id)
                ->where('status', 'pending')
                ->sum('amount'),
            'total_payouts' => Payout::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        return view('admin.payouts.teacher-earnings', compact('teacher', 'commissions', 'stats'));
    }
}

