<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WacanaScript extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'script_text',
        'paragraph_count',
    ];

    protected $casts = [
        'paragraph_count' => 'integer',
    ];
}
