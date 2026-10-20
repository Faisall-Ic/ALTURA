<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryoutSubtest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tryout_id',
        'name',
        'question_count',
        'duration_minutes',
    ];

    protected $casts = [
        'question_count' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }
}
