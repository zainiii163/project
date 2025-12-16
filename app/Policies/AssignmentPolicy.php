<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function view(User $user, Assignment $assignment)
    {
        return $user->isAdmin() || 
               ($user->isTeacher() && $assignment->course->teacher_id === $user->id) ||
               ($user->isStudent() && $assignment->course->students()->where('user_id', $user->id)->exists());
    }
}

