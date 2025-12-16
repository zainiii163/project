<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id || $user->isAdmin() || $user->isSuperAdmin();
    }

    public function update(User $user, Order $order)
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }
}

