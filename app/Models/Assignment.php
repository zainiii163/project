<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'course_id',
        'student_id',
        'title',
        'description',
        'content',
        'file_path',
        'due_date',
        'submitted_at',
        'submission_type',
        'max_score',
        'grade',
        'feedback',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}

