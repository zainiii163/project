<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'profile_picture',
        'registration_date',
        'last_login',
        'password_changed_at',
        'approved_at',
        'xp_points',
        'level',
        'referral_code',
        'referred_by',
        'bio',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'registration_date' => 'datetime',
        'last_login' => 'datetime',
        'password_changed_at' => 'datetime',
        'approved_at' => 'datetime',
        'deleted_at' => 'datetime',
        'xp_points' => 'integer',
        'level' => 'integer',
    ];

    // Relationships
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot('enrolled_at', 'progress', 'completed_at')
            ->withTimestamps();
    }

    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'student_id');
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_user')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('course_id', 'earned_at')
            ->withTimestamps();
    }

    public function xpTransactions()
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function liveSessions()
    {
        return $this->hasMany(LiveSession::class, 'teacher_id');
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function assignedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    // Referral relationships (using Referral model)
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    // Self-referencing relationship for referred users (using User model)
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'teacher_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'teacher_id');
    }

    // Helper methods
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
