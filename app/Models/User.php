<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the student profile associated with the user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the parent guardian profile associated with the user.
     */
    public function parentGuardian(): HasOne
    {
        return $this->hasOne(ParentGuardian::class);
    }

    /**
     * Get the students counseled by this user (counselor role).
     */
    public function counseledStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'counselor_id');
    }

    /**
     * Get the counseling slots opened by this counselor user.
     */
    public function counselingSlots(): HasMany
    {
        return $this->hasMany(CounselingSlot::class, 'counselor_user_id');
    }

    /**
     * Get the children associated with this parent user.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_user_id');
    }

    /**
     * Get attendances scanned by this admin user (Front Desk Admin - Rule 2).
     */
    public function scannedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'scanned_by_admin_id');
    }

    /**
     * Get subject consultation slots created by this admin user (Rule 6).
     */
    public function createdSubjectConsultationSlots(): HasMany
    {
        return $this->hasMany(SubjectConsultationSlot::class, 'created_by_admin_id');
    }

    /**
     * Self-referencing relation for backwards compatibility with legacy teacher-user eager loading.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id');
    }

    /**
     * Get the attendance flags resolved by this counselor user.
     */
    public function resolvedAttendanceFlags(): HasMany
    {
        return $this->hasMany(AttendanceFlag::class, 'resolved_by_counselor_id');
    }
}
