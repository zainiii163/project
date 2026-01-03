<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\MembershipPlan;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionBillingService
{
    public function processRecurringBilling()
    {
        // Get all active subscriptions that need billing
        $subscriptions = Subscription::where('status', 'active')
            ->where('next_billing_date', '<=', now())
            ->whereNotNull('next_billing_date')
            ->with(['user', 'membershipPlan'])
            ->get();

        foreach ($subscriptions as $subscription) {
            try {
                $this->chargeSubscription($subscription);
            } catch (\Exception $e) {
                Log::error('Subscription billing failed', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function chargeSubscription(Subscription $subscription)
    {
        $user = $subscription->user;
        $plan = $subscription->membershipPlan;

        if (!$plan) {
            $subscription->update(['status' => 'cancelled']);
            return;
        }

        // Create order for recurring payment
        $order = Order::create([
            'user_id' => $user->id,
            'order_date' => now(),
            'total_price' => $plan->price,
            'status' => 'pending',
        ]);

        // Process payment based on payment method
        $paymentMethod = $subscription->payment_method ?? 'credit_card';
        $success = $this->processPayment($order, $paymentMethod, $subscription);

        if ($success) {
            // Update subscription dates
            $nextBillingDate = $this->calculateNextBillingDate($subscription, $plan);
            
            $subscription->update([
                'next_billing_date' => $nextBillingDate,
                'end_date' => $nextBillingDate,
            ]);

            // Create transaction record
            Transaction::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'amount' => $plan->price,
                'status' => 'completed',
                'transaction_date' => now(),
                'transaction_id' => 'SUB-' . strtoupper(uniqid()),
                'notes' => 'Recurring subscription payment for ' . $plan->name,
            ]);

            $order->update(['status' => 'completed']);

            // TODO: Send payment confirmation email
        } else {
            // Payment failed - handle accordingly
            $this->handlePaymentFailure($subscription, $order);
        }
    }

    private function processPayment(Order $order, $paymentMethod, Subscription $subscription)
    {
        // TODO: Integrate with actual payment gateways (Stripe, PayPal, etc.)
        // For now, simulate payment processing
        
        if ($paymentMethod === 'wallet') {
            $wallet = $order->user->wallet;
            if ($wallet && $wallet->balance >= $order->total_price) {
                $wallet->debit($order->total_price);
                return true;
            }
            return false;
        }

        // Placeholder for other payment methods
        // Stripe: \Stripe\Subscription::retrieve($subscription->subscription_id)->update(...)
        // PayPal: Process recurring payment
        
        return true; // Simulate success for now
    }

    private function calculateNextBillingDate(Subscription $subscription, MembershipPlan $plan)
    {
        $currentDate = $subscription->next_billing_date ?? now();

        switch ($plan->billing_cycle) {
            case 'monthly':
                return $currentDate->addMonth();
            case 'quarterly':
                return $currentDate->addMonths(3);
            case 'yearly':
                return $currentDate->addYear();
            case 'lifetime':
                return null; // No next billing for lifetime
            default:
                return $currentDate->addMonth();
        }
    }

    private function handlePaymentFailure(Subscription $subscription, Order $order)
    {
        // Mark order as failed
        $order->update(['status' => 'failed']);

        // Create failed transaction
        Transaction::create([
            'order_id' => $order->id,
            'payment_method' => $subscription->payment_method ?? 'credit_card',
            'amount' => $order->total_price,
            'status' => 'failed',
            'transaction_date' => now(),
            'notes' => 'Recurring payment failed',
        ]);

        // Give grace period (e.g., 3 days) before cancelling
        $gracePeriodEnd = now()->addDays(3);
        
        if ($subscription->end_date < $gracePeriodEnd) {
            $subscription->update([
                'end_date' => $gracePeriodEnd,
                'status' => 'active', // Keep active during grace period
            ]);
        }

        // TODO: Send payment failure notification
        // TODO: After grace period, cancel subscription if payment still fails
    }

    public function cancelSubscription(Subscription $subscription, $reason = null)
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // TODO: Cancel subscription in payment gateway
        // Stripe: \Stripe\Subscription::retrieve($subscription->subscription_id)->cancel()
        // PayPal: Cancel recurring payment

        // TODO: Send cancellation confirmation email
    }

    public function renewSubscription(Subscription $subscription)
    {
        if ($subscription->status !== 'expired') {
            return false;
        }

        $plan = $subscription->membershipPlan;
        if (!$plan) {
            return false;
        }

        // Create new subscription period
        $nextBillingDate = $this->calculateNextBillingDate($subscription, $plan);

        $subscription->update([
            'status' => 'active',
            'start_date' => now(),
            'end_date' => $nextBillingDate,
            'next_billing_date' => $nextBillingDate,
            'cancelled_at' => null,
        ]);

        return true;
    }
}

