<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicClass extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'major_id',
        'name',
        'code',
        'grade_level',
        'is_tka_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade_level' => 'integer',
            'is_tka_active' => 'boolean',
        ];
    }

    /**
     * Deactivate TKA subjects for post-October curriculum transition (Rule 9).
     */
    public function deactivateTka(): void
    {
        $this->update(['is_tka_active' => false]);
    }

    /**
     * Activate TKA subjects for pre-October curriculum (Rule 9).
     */
    public function activateTka(): void
    {
        $this->update(['is_tka_active' => true]);
    }

    /**
     * Get available subjects based on is_tka_active state.
     */
    public function availableSubjects(): Collection
    {
        $major = $this->major;
        if (! $major) {
            return new Collection;
        }

        $query = $major->subjects();
        if (! $this->is_tka_active) {
            $query->where('is_tka', false);
        }

        return $query->get();
    }

    /**
     * Get the major that owns this academic class.
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * Get the students enrolled in this academic class.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Get the teachers assigned to this class.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_class', 'class_id', 'teacher_id');
    }

    /**
     * Get the lesson schedules for this class.
     */
    public function lessonSchedules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class, 'class_id');
    }
}
