<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Services\SubscriptionBillingService;
use Illuminate\Http\Request;

class StudentSubscriptionController extends Controller
{
    protected $billingService;

    public function __construct(SubscriptionBillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        $plans = MembershipPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $student = auth()->user();
        $activeSubscription = $student->subscriptions()
            ->where('status', 'active')
            ->with('membershipPlan')
            ->first();

        return view('student.subscriptions.index', compact('plans', 'activeSubscription'));
    }

    public function subscribe(Request $request, MembershipPlan $membershipPlan)
    {
        $student = auth()->user();

        // Check if user already has an active subscription
        $activeSubscription = $student->subscriptions()
            ->where('status', 'active')
            ->first();

        if ($activeSubscription) {
            return back()->with('error', 'You already have an active subscription. Please cancel it first.');
        }

        // Calculate dates based on billing cycle
        $startDate = now();
        $endDate = $this->calculateEndDate($startDate, $membershipPlan->billing_cycle, $membershipPlan->duration_days);
        $nextBillingDate = $membershipPlan->billing_cycle !== 'lifetime' 
            ? $this->calculateNextBillingDate($startDate, $membershipPlan->billing_cycle)
            : null;

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $student->id,
            'plan_id' => $membershipPlan->id,
            'plan' => $membershipPlan->name,
            'amount' => $membershipPlan->price,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'next_billing_date' => $nextBillingDate,
            'status' => 'pending', // Will be active after payment
            'billing_cycle' => $membershipPlan->billing_cycle,
            'payment_method' => $request->input('payment_method', 'credit_card'),
        ]);

        // Create order for initial payment
        $order = \App\Models\Order::create([
            'user_id' => $student->id,
            'order_date' => now(),
            'total_price' => $membershipPlan->price,
            'status' => 'pending',
        ]);

        // If all-access plan, enroll in all courses
        if ($membershipPlan->is_all_access) {
            $courses = \App\Models\Course::where('status', 'published')->pluck('id');
            $subscription->courses()->attach($courses);
        } else {
            // Attach specific courses
            if ($membershipPlan->courses->isNotEmpty()) {
                $subscription->courses()->attach($membershipPlan->courses->pluck('id'));
            }
        }

        return redirect()->route('student.payments.process', $order)
            ->with('success', 'Please complete payment to activate your subscription.');
    }

    public function cancel(Subscription $subscription)
    {
        $student = auth()->user();

        if ($subscription->user_id !== $student->id) {
            abort(403);
        }

        $this->billingService->cancelSubscription($subscription, 'User requested cancellation');

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    public function renew(Subscription $subscription)
    {
        $student = auth()->user();

        if ($subscription->user_id !== $student->id) {
            abort(403);
        }

        if ($this->billingService->renewSubscription($subscription)) {
            return back()->with('success', 'Subscription renewed successfully.');
        }

        return back()->with('error', 'Unable to renew subscription.');
    }

    private function calculateEndDate($startDate, $billingCycle, $durationDays = null)
    {
        if ($durationDays) {
            return $startDate->copy()->addDays($durationDays);
        }

        switch ($billingCycle) {
            case 'monthly':
                return $startDate->copy()->addMonth();
            case 'quarterly':
                return $startDate->copy()->addMonths(3);
            case 'yearly':
                return $startDate->copy()->addYear();
            case 'lifetime':
                return $startDate->copy()->addYears(100); // Effectively lifetime
            default:
                return $startDate->copy()->addMonth();
        }
    }

    private function calculateNextBillingDate($startDate, $billingCycle)
    {
        switch ($billingCycle) {
            case 'monthly':
                return $startDate->copy()->addMonth();
            case 'quarterly':
                return $startDate->copy()->addMonths(3);
            case 'yearly':
                return $startDate->copy()->addYear();
            default:
                return null;
        }
    }
}

