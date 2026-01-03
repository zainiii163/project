<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Resource $resource)
    {
        return $resource->is_public || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, Resource $resource)
    {
        return $user->id === $resource->uploaded_by || $user->isAdmin();
    }

    public function delete(User $user, Resource $resource)
    {
        return $user->id === $resource->uploaded_by || $user->isAdmin();
    }
}

