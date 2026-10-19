<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'phone_number',
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
     * Get the subjects taught by this teacher.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    /**
     * Get the classes taught by this teacher.
     */
    public function academicClasses(): BelongsToMany
    {
        return $this->belongsToMany(AcademicClass::class, 'teacher_class', 'teacher_id', 'class_id');
    }

    /**
     * Alias for academicClasses.
     */
    public function classes(): BelongsToMany
    {
        return $this->academicClasses();
    }

    /**
     * Get the mentee students assigned to this teacher/mentor (Dual-role: Pembimbing Akademik).
     */
    public function mentees(): HasMany
    {
        return $this->hasMany(Student::class, 'mentor_id');
    }

    /**
     * Alias for mentees.
     */
    public function students(): HasMany
    {
        return $this->mentees();
    }

    /**
     * Get the lesson schedules for this teacher (Dual-role: Guru Mapel).
     */
    public function lessonSchedules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class);
    }

    /**
     * Get the subject consultation (tentoring) slots opened by this teacher.
     */
    public function subjectConsultationSlots(): HasMany
    {
        return $this->hasMany(SubjectConsultationSlot::class);
    }

    /**
     * Get the monthly submission alerts triggered for this mentor's mentees.
     */
    public function monthlySubmissionAlerts(): HasMany
    {
        return $this->hasMany(MonthlySubmissionAlert::class, 'mentor_id');
    }

    /**
     * Get the attendances scanned by this teacher (legacy).
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
