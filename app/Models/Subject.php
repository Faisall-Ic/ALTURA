<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_tka',
        'description',
    ];

    protected $casts = [
        'is_tka' => 'boolean',
    ];

    /**
     * Majors that include this subject.
     */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'major_subject');
    }

    /**
     * Teachers assigned to teach this subject.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject');
    }

    /**
     * Lesson schedules for this subject.
     */
    public function lessonSchedules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class);
    }

    /**
     * ALTER LMS courses for this subject.
     */
    public function alterCourses(): HasMany
    {
        return $this->hasMany(AlterCourse::class);
    }

    /**
     * Homework assignments for this subject.
     */
    public function homeworkAssignments(): HasMany
    {
        return $this->hasMany(HomeworkAssignment::class);
    }

    /**
     * Subject consultation (tentoring) slots.
     */
    public function subjectConsultationSlots(): HasMany
    {
        return $this->hasMany(SubjectConsultationSlot::class);
    }
}
