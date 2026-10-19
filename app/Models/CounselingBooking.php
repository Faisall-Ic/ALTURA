<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselingBooking extends Model
{
    use HasFactory;

    public const MAX_QUOTA_PER_SEMESTER = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slot_id',
        'student_id',
        'parent_id',
        'semester',
        'year',
        'booking_type',
        'notes',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'semester' => 'integer',
            'year' => 'integer',
        ];
    }

    /**
     * Check if a student can book counseling in the specified semester (Max 5 per semester).
     */
    public static function canStudentBook(int $studentId, int $semester, int $year): bool
    {
        return static::remainingQuotaForStudent($studentId, $semester, $year) > 0;
    }

    /**
     * Calculate remaining quota for a student in a semester (Max 5).
     */
    public static function remainingQuotaForStudent(int $studentId, int $semester, int $year): int
    {
        $usedCount = static::where('student_id', $studentId)
            ->where('semester', $semester)
            ->where('year', $year)
            ->whereIn('status', ['booked', 'attended', 'completed'])
            ->count();

        return max(0, self::MAX_QUOTA_PER_SEMESTER - $usedCount);
    }

    /**
     * Get the counseling slot for this booking.
     */
    public function slot(): BelongsTo
    {
        return $this->belongsTo(CounselingSlot::class, 'slot_id');
    }

    /**
     * Get the student for this booking.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the parent/guardian involved in this booking.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentGuardian::class, 'parent_id');
    }
}
