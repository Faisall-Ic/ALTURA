<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'is_tka',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_tka' => 'boolean',
        ];
    }

    /**
     * Scope to TKA subjects (Fisika, Kimia, Biologi, Sosiologi, etc.).
     */
    public function scopeTka(Builder $query): Builder
    {
        return $query->where('is_tka', true);
    }

    /**
     * Scope to SNBT subjects (TPS, Literasi, Penalaran Matematika).
     */
    public function scopeSnbt(Builder $query): Builder
    {
        return $query->where('is_tka', false);
    }

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
     * Subject consultation (tentoring) slots.
     */
    public function subjectConsultationSlots(): HasMany
    {
        return $this->hasMany(SubjectConsultationSlot::class);
    }
}
