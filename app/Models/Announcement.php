<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'content',
        'scope',
        'course_id',
        'user_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'announcement_user')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'announcement_user')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }
}

