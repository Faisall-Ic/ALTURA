<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'lesson_schedule_id',
        'date',
        'time_in',
        'session_time',
        'status',
        'scanned_by_admin_id',
        'scanned_by_teacher_id',
        'remarks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Attendance $attendance) {
            if (! $attendance->scanned_by_admin_id) {
                $adminId = auth()->id()
                    ?? User::role(['front_desk', 'admin'])->first()?->id
                    ?? User::first()?->id;
                if ($adminId) {
                    $attendance->scanned_by_admin_id = $adminId;
                }
            }

            if (! $attendance->time_in && $attendance->session_time) {
                $attendance->time_in = $attendance->session_time;
            }
        });
    }

    /**
     * Get the student for this attendance record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the lesson schedule for this attendance record.
     */
    public function lessonSchedule(): BelongsTo
    {
        return $this->belongsTo(LessonSchedule::class, 'lesson_schedule_id');
    }

    /**
     * Get the front desk administrator who scanned the student (Rule 2).
     */
    public function scannedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by_admin_id');
    }

    /**
     * General scanned by relation (prefers admin user, falls back to teacher user).
     */
    public function scannedBy(): BelongsTo
    {
        return $this->scannedByAdmin();
    }

    /**
     * Alias for backwards compatibility with existing views.
     */
    public function scannedByTeacher(): BelongsTo
    {
        if ($this->scanned_by_admin_id) {
            return $this->belongsTo(User::class, 'scanned_by_admin_id');
        }

        return $this->belongsTo(Teacher::class, 'scanned_by_teacher_id');
    }
}
