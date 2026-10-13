<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorytellingScript extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'script_text',
        'target_duration_minutes',
    ];

    protected $casts = [
        'target_duration_minutes' => 'integer',
    ];
}
