<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isTeacher();
    }
}

