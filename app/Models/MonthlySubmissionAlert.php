<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlySubmissionAlert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'mentor_id',
        'month',
        'year',
        'missing_tasks',
        'is_resolved',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'missing_tasks' => 'array',
            'is_resolved' => 'boolean',
        ];
    }

    /**
     * Get the mentee student associated with the alert.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the mentor teacher associated with the alert.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'mentor_id');
    }

    /**
     * Check student submissions on the 20th of the month and record alert if missing.
     *
     * @return static|null
     */
    public static function evaluateMonthlySubmissions(Student $student, int $month, int $year): ?self
    {
        if (! $student->mentor_id) {
            return null;
        }

        $missing = [];

        // 1. Check Vocab Quiz submission in this month
        $hasVocab = $student->vocabQuizResults()
            ->whereYear('completed_at', $year)
            ->whereMonth('completed_at', $month)
            ->exists();
        if (! $hasVocab) {
            $missing[] = 'Vocab Quiz';
        }

        // 2. Check Storytelling submission in this month
        $hasStorytelling = $student->storytellingSubmissions()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->exists();
        if (! $hasStorytelling) {
            $missing[] = 'Storytelling';
        }

        // 3. Check Wacana submission (if applicable to student)
        if (WacanaText::appliesToStudent($student)) {
            $hasWacana = $student->wacanaSubmissions()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->exists();
            if (! $hasWacana) {
                $missing[] = 'Wacana';
            }
        }

        // 4. Check Speaking Booking / ALTER
        $hasSpeaking = $student->speakingBookings()
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->exists();
        if (! $hasSpeaking) {
            $missing[] = 'Speaking Assessment';
        }

        if (empty($missing)) {
            return null;
        }

        return static::updateOrCreate(
            [
                'student_id' => $student->id,
                'mentor_id' => $student->mentor_id,
                'month' => $month,
                'year' => $year,
            ],
            [
                'missing_tasks' => $missing,
                'is_resolved' => false,
            ]
        );
    }
}
