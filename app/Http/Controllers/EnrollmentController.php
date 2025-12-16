<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, Course $course)
    {
        $user = auth()->user();

        // Check if already enrolled
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Free course
        if ($course->price == 0) {
            $course->students()->attach($user->id, [
                'enrolled_at' => now(),
                'progress' => 0,
            ]);

            return back()->with('success', 'Successfully enrolled in the course!');
        }

        // Paid course - create order
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'order_date' => now(),
                'total_price' => $course->price,
                'status' => 'pending',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'course_id' => $course->id,
                'price' => $course->price,
                'quantity' => 1,
            ]);

            DB::commit();

            return redirect()->route('payment.process', $order)
                ->with('success', 'Please complete the payment to enroll.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create enrollment. Please try again.');
        }
    }
}

