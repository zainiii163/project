<?php

namespace App\Policies;

use App\Models\Discussion;
use App\Models\User;

class DiscussionPolicy
{
    public function update(User $user, Discussion $discussion)
    {
        return $user->id === $discussion->user_id || $user->isAdmin() || $user->isTeacher();
    }

    public function delete(User $user, Discussion $discussion)
    {
        return $user->id === $discussion->user_id || $user->isAdmin() || $user->isTeacher();
    }
}

