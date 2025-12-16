<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'content_url',
        'type',
        'duration',
        'order',
        'is_preview',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}

