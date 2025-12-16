<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    public function view(User $user, Certificate $certificate)
    {
        return $user->id === $certificate->user_id || $user->isAdmin() || $user->isTeacher();
    }
}

