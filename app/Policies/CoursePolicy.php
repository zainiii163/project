<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Course $course)
    {
        // Anyone can view published courses
        if ($course->status === 'published') {
            return true;
        }

        // Teachers can view their own courses
        if ($user->isTeacher() && $course->teacher_id === $user->id) {
            return true;
        }

        // Admins can view all courses
        if ($user->isAdmin()) {
            return true;
        }

        // Students can view courses they're enrolled in
        if ($user->isStudent() && $course->students()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, Course $course)
    {
        return $user->isAdmin() || ($user->isTeacher() && $course->teacher_id === $user->id);
    }

    public function delete(User $user, Course $course)
    {
        return $user->isAdmin() || ($user->isTeacher() && $course->teacher_id === $user->id);
    }
}

