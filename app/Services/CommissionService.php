<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Commission;
use App\Models\Course;

class CommissionService
{
    protected $defaultCommissionRate = 0.30; // 30% default

    public function calculateAndCreateCommissions(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->course && $item->course->teacher_id) {
                $this->createCommission($order, $item->course, $item->price * $item->quantity);
            }
        }
    }

    private function createCommission(Order $order, Course $course, $amount)
    {
        // Get commission rate (could be from course settings, teacher settings, or default)
        $commissionRate = $this->getCommissionRate($course);
        $commissionAmount = $amount * $commissionRate;

        Commission::create([
            'teacher_id' => $course->teacher_id,
            'order_id' => $order->id,
            'course_id' => $course->id,
            'amount' => $commissionAmount,
            'commission_rate' => $commissionRate * 100, // Store as percentage
            'status' => 'pending',
        ]);
    }

    private function getCommissionRate(Course $course)
    {
        // Check course-specific rate
        if ($course->commission_rate) {
            return $course->commission_rate / 100;
        }

        // Check teacher-specific rate
        if ($course->teacher && $course->teacher->commission_rate) {
            return $course->teacher->commission_rate / 100;
        }

        // Use default
        return $this->defaultCommissionRate;
    }

    public function getPendingEarnings($teacherId)
    {
        return Commission::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->sum('amount');
    }

    public function getTotalEarnings($teacherId)
    {
        return Commission::where('teacher_id', $teacherId)
            ->where('status', 'paid')
            ->sum('amount');
    }
}

