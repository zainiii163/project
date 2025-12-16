<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;

class BlogPostPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, BlogPost $post)
    {
        return $post->status === 'published' || $user->id === $post->author_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, BlogPost $post)
    {
        return $user->id === $post->author_id || $user->isAdmin();
    }

    public function delete(User $user, BlogPost $post)
    {
        return $user->id === $post->author_id || $user->isAdmin();
    }
}

