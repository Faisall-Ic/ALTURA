<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectConsultationSlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'created_by_admin_id',
        'topic',
        'date_time',
        'room_or_link',
        'max_quota',
        'remaining_quota',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_time' => 'datetime',
            'max_quota' => 'integer',
            'remaining_quota' => 'integer',
        ];
    }

    /**
     * Get the subject for this consultation slot.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher providing this consultation.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the admin user who created this slot (Rule 6: created exclusively by ADMIN).
     */
    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    /**
     * Get the student bookings for this slot.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(SubjectConsultationBooking::class, 'slot_id');
    }
}
