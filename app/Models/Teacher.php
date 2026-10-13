<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nip',
        'specialization_subject',
    ];

    /**
     * Get the user account for this teacher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the mentee students assigned to this teacher/mentor.
     */
    public function mentees(): HasMany
    {
        return $this->hasMany(Student::class, 'mentor_id');
    }

    /**
     * Get the attendances scanned by this teacher.
     */
    public function scannedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'scanned_by_teacher_id');
    }

    /**
     * Get the consultation slots opened by this mentor.
     */
    public function consultationSlots(): HasMany
    {
        return $this->hasMany(ConsultationSlot::class, 'mentor_id');
    }

    /**
     * Get the speaking exam bookings assigned to this mentor.
     */
    public function speakingBookings(): HasMany
    {
        return $this->hasMany(SpeakingBooking::class, 'mentor_id');
    }
}
