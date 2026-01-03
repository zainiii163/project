<?php

namespace App\Policies;

use App\Models\CalendarEvent;
use App\Models\User;

class CalendarEventPolicy
{
    public function view(User $user, CalendarEvent $event)
    {
        return $user->id === $event->user_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create calendar events
    }

    public function update(User $user, CalendarEvent $event)
    {
        return $user->id === $event->user_id || $user->isAdmin();
    }

    public function delete(User $user, CalendarEvent $event)
    {
        return $user->id === $event->user_id || $user->isAdmin();
    }
}

