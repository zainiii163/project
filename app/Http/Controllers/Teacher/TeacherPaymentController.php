<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Payout;
use Illuminate\Http\Request;

class TeacherPaymentController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();
        
        $commissions = Commission::where('teacher_id', $teacher->id)
            ->with(['order.user', 'course'])
            ->latest()
            ->paginate(20);

        $payouts = Payout::where('teacher_id', $teacher->id)
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
            'pending_payouts' => Payout::where('teacher_id', $teacher->id)
                ->whereIn('status', ['pending', 'processing'])
                ->sum('amount'),
        ];

        return view('teacher.payments.index', compact('commissions', 'payouts', 'stats'));
    }

    public function commissions()
    {
        $teacher = auth()->user();
        
        $commissions = Commission::where('teacher_id', $teacher->id)
            ->with(['order.user', 'course'])
            ->latest()
            ->paginate(20);

        return view('teacher.payments.commissions', compact('commissions'));
    }

    public function payouts()
    {
        $teacher = auth()->user();
        
        $payouts = Payout::where('teacher_id', $teacher->id)
            ->with('commissions')
            ->latest()
            ->paginate(20);

        return view('teacher.payments.payouts', compact('payouts'));
    }

    public function showPayout(Payout $payout)
    {
        $teacher = auth()->user();
        
        if ($payout->teacher_id !== $teacher->id) {
            abort(403);
        }

        $payout->load(['commissions.order', 'commissions.course']);

        return view('teacher.payments.show-payout', compact('payout'));
    }
}

