<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeakingAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'grade',
        'rubric_scores',
        'notes',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'rubric_scores' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(SpeakingBooking::class, 'booking_id');
    }
}
