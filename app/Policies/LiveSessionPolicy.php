<?php

namespace App\Policies;

use App\Models\LiveSession;
use App\Models\User;

class LiveSessionPolicy
{
    public function view(User $user, LiveSession $session)
    {
        // Teachers can view their own sessions
        if ($user->isTeacher() && $user->id === $session->teacher_id) {
            return true;
        }

        // Students can view if enrolled in the course
        if ($user->isStudent()) {
            return $session->course->students()->where('user_id', $user->id)->exists();
        }

        // Admins can view all
        return $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, LiveSession $session)
    {
        return ($user->isTeacher() && $user->id === $session->teacher_id) || $user->isAdmin();
    }

    public function delete(User $user, LiveSession $session)
    {
        return ($user->isTeacher() && $user->id === $session->teacher_id) || $user->isAdmin();
    }
}

