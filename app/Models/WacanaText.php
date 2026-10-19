<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WacanaText extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'paragraph_count',
    ];

    /**
     * Get the submissions for this wacana text.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(WacanaSubmission::class, 'wacana_id');
    }

    /**
     * Check if Wacana applies to a given student at a given date.
     * Rule: Wacana applies ONLY to SNBT 1 & SNBT 2 before October TKA.
     * After October (month >= 11), it automatically applies to ALL Grade 12 students.
     */
    public static function appliesToStudent(Student $student, ?Carbon $date = null): bool
    {
        $date = $date ?: Carbon::now();
        $class = $student->academicClass;

        if (! $class) {
            return false;
        }

        // Post-October (November onwards, or when is_tka_active is false)
        if ($date->month >= 11 || ! $class->is_tka_active) {
            return $class->grade_level == 12;
        }

        // Pre-November / Before post-October: Applies only to SNBT 1 & SNBT 2
        return in_array($class->code, ['SNBT-01', 'SNBT-02', '12-SNBT-01', '12-SNBT-02'])
            || str_contains(strtoupper($class->name), 'SNBT');
    }
}
