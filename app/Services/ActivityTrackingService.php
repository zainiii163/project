<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityTrackingService
{
    public function log($action, $model = null, $oldValues = null, $newValues = null)
    {
        $user = Auth::user();

        AuditLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }

    public function logCreate($model)
    {
        $this->log('created', $model, null, $model->getAttributes());
    }

    public function logUpdate($model, $oldValues)
    {
        $this->log('updated', $model, $oldValues, $model->getAttributes());
    }

    public function logDelete($model)
    {
        $this->log('deleted', $model, $model->getAttributes(), null);
    }

    public function logLogin($user)
    {
        $this->log('login', $user);
    }

    public function logLogout($user)
    {
        $this->log('logout', $user);
    }

    public function logPayment($order)
    {
        $this->log('payment_completed', $order, null, [
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'status' => $order->status,
        ]);
    }

    public function logEnrollment($user, $course)
    {
        $this->log('enrolled', $course, null, [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }
}

