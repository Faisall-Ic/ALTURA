<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectConsultationSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'topic',
        'date_time',
        'room_or_link',
        'max_quota',
        'remaining_quota',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'max_quota' => 'integer',
        'remaining_quota' => 'integer',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(SubjectConsultationBooking::class, 'slot_id');
    }
}
