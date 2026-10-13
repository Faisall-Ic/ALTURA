<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'room',
        'day_of_week',
        'time_start',
        'time_end',
        'note',
    ];

    /**
     * The academic class this schedule belongs to.
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * The subject for this lesson.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * The teacher assigned to this lesson.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Attendances recorded for this lesson session.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'lesson_schedule_id');
    }
}
