<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'teacher_id',
        'category_id',
        'price',
        'status',
        'visibility',
        'level',
        'duration',
        'thumbnail',
        'objectives',
        'requirements',
        'prerequisites',
        'skill_tags',
        'content_type',
        'scheduled_publish_at',
        'approved_at',
        'archived_at',
        'rejection_reason',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot('enrolled_at', 'progress', 'completed_at')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_course');
    }

    // Helper methods
    public function publish()
    {
        $this->update(['status' => 'published']);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalEnrollmentsAttribute()
    {
        return $this->students()->count();
    }
}

