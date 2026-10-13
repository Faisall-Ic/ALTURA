<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nisn',
        'major_id',
        'class_id',
        'mentor_id',
        'counselor_id',
        'parent_user_id',
        'parent_phone_number',
        'status_active',
        'qr_identity_token',
        'qr_code_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_active' => 'boolean',
        ];
    }

    /**
     * Get the user account associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the major the student belongs to.
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * Get the academic class the student belongs to.
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * Get the designated mentor (teacher) for the student.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'mentor_id');
    }

    /**
     * Get the designated counselor (BK user) for the student.
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    /**
     * Get the attendance records for the student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the consultation bookings made by the student.
     */
    public function consultationBookings(): HasMany
    {
        return $this->hasMany(ConsultationBooking::class, 'student_id');
    }

    /**
     * Get the vocabulary quiz results for the student.
     */
    public function vocabQuizResults(): HasMany
    {
        return $this->hasMany(VocabQuizResult::class, 'student_id');
    }

    /**
     * Get the speaking exam bookings made by the student.
     */
    public function speakingBookings(): HasMany
    {
        return $this->hasMany(SpeakingBooking::class, 'student_id');
    }

    /**
     * Get the registered parent (user account) for the student.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    /**
     * Get the automated attendance flags for the student.
     */
    public function attendanceFlags(): HasMany
    {
        return $this->hasMany(AttendanceFlag::class, 'student_id');
    }

    /**
     * Get the parents/guardians linked to this student.
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentGuardian::class, 'student_parent', 'student_id', 'parent_id')
            ->withPivot('relationship_type')
            ->withTimestamps();
    }

    /**
     * Get homework submissions by this student.
     */
    public function homeworkSubmissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class, 'student_id');
    }

    /**
     * Get subject consultation (tentoring) bookings made by the student.
     */
    public function subjectConsultationBookings(): HasMany
    {
        return $this->hasMany(SubjectConsultationBooking::class, 'student_id');
    }

    /**
     * Get counseling bookings (BK) made by or for the student.
     */
    public function counselingBookings(): HasMany
    {
        return $this->hasMany(CounselingBooking::class, 'student_id');
    }

    /**
     * Accessor for qr_code_token.
     */
    public function getQrCodeTokenAttribute(?string $value): ?string
    {
        return $value ?: $this->attributes['qr_identity_token'] ?? null;
    }
}
