<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function view(User $user, User $model)
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->id === $model->id;
    }

    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function update(User $user, User $model)
    {
        // Super admin can update anyone
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin can update anyone except super admin
        if ($user->isAdmin() && $model->role !== 'super_admin') {
            return true;
        }
        
        // Users can update themselves
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        // Super admin can delete anyone
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin can delete anyone except super admin and other admins
        if ($user->isAdmin() && !in_array($model->role, ['super_admin', 'admin'])) {
            return true;
        }
        
        return false;
    }
}

