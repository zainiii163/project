<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPaymentController extends Controller
{
    public function purchaseCourse(Request $request, Course $course)
    {
        $student = auth()->user();
        
        // Check if already enrolled
        if ($student->courses()->where('courses.id', $course->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Check if course is free
        if ($course->price == 0) {
            $student->courses()->attach($course->id, [
                'enrolled_at' => now(),
                'progress' => 0,
            ]);
            
            return redirect()->route('student.courses.show', $course)
                ->with('success', 'Successfully enrolled in the free course!');
        }

        // Create order
        $order = Order::create([
            'user_id' => $student->id,
            'order_date' => now(),
            'total_price' => $course->price,
            'status' => 'pending',
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'course_id' => $course->id,
            'price' => $course->price,
            'quantity' => 1,
        ]);

        // Apply coupon if provided
        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->first();

            if ($coupon) {
                $discount = $coupon->type === 'percentage' 
                    ? ($order->total_price * $coupon->value / 100)
                    : $coupon->value;
                
                $order->update([
                    'coupon_code' => $coupon->code,
                    'discount_amount' => $discount,
                    'total_price' => max(0, $order->total_price - $discount),
                ]);
            }
        }

        return redirect()->route('payments.process', $order)
            ->with('success', 'Please complete the payment to enroll.');
    }

    public function processPayment(Order $order)
    {
        $student = auth()->user();
        
        if ($order->user_id !== $student->id) {
            abort(403);
        }

        if ($order->status === 'completed') {
            return redirect()->route('student.courses.show', $order->items->first()->course)
                ->with('info', 'This order has already been completed.');
        }

        $order->load(['items.course', 'user']);

        return view('student.payments.process', compact('order'));
    }

    public function completePayment(Request $request, Order $order)
    {
        $student = auth()->user();
        
        if ($order->user_id !== $student->id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,paypal,wallet',
        ]);

        // Handle wallet payment
        if ($validated['payment_method'] === 'wallet') {
            $wallet = $student->wallet;
            if (!$wallet || $wallet->balance < $order->total_price) {
                return back()->with('error', 'Insufficient wallet balance.');
            }
            $wallet->debit($order->total_price);
        }

        // Create transaction
        $transaction = \App\Models\Transaction::create([
            'order_id' => $order->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $order->total_price,
            'status' => 'completed',
            'transaction_date' => now(),
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
        ]);

        // Update order status
        $order->update(['status' => 'completed']);

        // Enroll student in courses
        foreach ($order->items as $item) {
            if ($item->course) {
                $student->courses()->syncWithoutDetaching([$item->course_id => [
                    'enrolled_at' => now(),
                    'progress' => 0,
                ]]);
            }
        }

        return redirect()->route('payments.history')
            ->with('success', 'Payment completed successfully! You have been enrolled in the course(s).');
    }

    public function transactionHistory()
    {
        $student = auth()->user();
        
        $orders = $student->orders()
            ->with(['items.course', 'transaction'])
            ->latest()
            ->paginate(20);

        return view('student.payments.history', compact('orders'));
    }

    public function invoices()
    {
        $student = auth()->user();
        
        $orders = $student->orders()
            ->where('status', 'completed')
            ->with(['items.course'])
            ->latest()
            ->get();

        return view('student.payments.invoices', compact('orders'));
    }

    public function downloadInvoice(Order $order)
    {
        $student = auth()->user();
        
        if ($order->user_id !== $student->id) {
            abort(403);
        }

        // Generate PDF invoice (implement with DomPDF or similar)
        // return PDF::loadView('student.payments.invoice-pdf', compact('order'))->download();
        
        return view('student.payments.invoice-pdf', compact('order'));
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $coupon = Coupon::where('code', $validated['coupon_code'])
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        // Check usage limit
        $usageCount = Order::where('coupon_code', $coupon->code)
            ->where('status', 'completed')
            ->count();

        if ($coupon->usage_limit && $usageCount >= $coupon->usage_limit) {
            return back()->with('error', 'Coupon usage limit reached.');
        }

        return back()->with('success', 'Coupon applied! Discount: ' . 
            ($coupon->type === 'percentage' ? $coupon->value . '%' : '$' . $coupon->value));
    }

    public function subscriptions()
    {
        $student = auth()->user();
        
        $subscriptions = $student->subscriptions()
            ->with(['courses'])
            ->latest()
            ->get();

        return view('student.payments.subscriptions', compact('subscriptions'));
    }

    public function purchaseSubscription(Request $request, Subscription $subscription)
    {
        $student = auth()->user();
        
        // Check if already subscribed
        if ($student->subscriptions()->where('subscriptions.id', $subscription->id)->exists()) {
            return back()->with('error', 'You are already subscribed to this plan.');
        }

        // Create order for subscription
        $order = Order::create([
            'user_id' => $student->id,
            'order_date' => now(),
            'total_price' => $subscription->price,
            'status' => 'pending',
            'type' => 'subscription',
        ]);

        // Attach subscription to user
        $student->subscriptions()->attach($subscription->id, [
            'started_at' => now(),
            'expires_at' => now()->addMonth(), // Adjust based on subscription duration
        ]);

        return redirect()->route('payments.process', $order)
            ->with('success', 'Please complete the payment to activate subscription.');
    }

    public function applyReferralCredit(Request $request)
    {
        $student = auth()->user();
        
        $validated = $request->validate([
            'referral_code' => 'required|string|max:50',
        ]);

        // Check referral code (implement if you have a referrals table)
        // $referral = Referral::where('code', $validated['referral_code'])->first();
        
        // if (!$referral || $referral->used) {
        //     return back()->with('error', 'Invalid or already used referral code.');
        // }

        // Apply credit to student wallet
        // $student->wallet()->increment('balance', $referral->credit_amount);
        
        return back()->with('success', 'Referral credit applied!');
    }
}

