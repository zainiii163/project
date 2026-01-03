<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'plan',
        'plan_id',
        'amount',
        'start_date',
        'end_date',
        'status',
        'billing_cycle',
        'next_billing_date',
        'cancelled_at',
        'payment_method',
        'subscription_id', // Payment gateway subscription ID
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'next_billing_date' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'subscription_course');
    }

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date > now();
    }

    public function isAllAccess()
    {
        return $this->membershipPlan && $this->membershipPlan->is_all_access;
    }
}

