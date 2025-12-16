<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'bookmarkable_id',
        'bookmarkable_type',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarkable()
    {
        return $this->morphTo();
    }
}




