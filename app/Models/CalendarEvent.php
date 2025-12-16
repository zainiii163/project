<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'course_id',
        'assignment_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
        'location',
        'meeting_url',
        'reminder_settings',
        'is_all_day',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'reminder_settings' => 'array',
        'is_all_day' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}




