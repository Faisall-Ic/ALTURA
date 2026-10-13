<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeakingBooking extends Model
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
        'speaking_task_id',
        'booking_date',
        'time_slot',
        'status',
        'grade',
        'rubric_pronunciation',
        'rubric_fluency',
        'rubric_vocabulary',
        'rubric_comprehension',
        'rubric_notes',
        'graded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'grade' => 'float',
            'graded_at' => 'datetime',
            'rubric_pronunciation' => 'integer',
            'rubric_fluency' => 'integer',
            'rubric_vocabulary' => 'integer',
            'rubric_comprehension' => 'integer',
        ];
    }

    /**
     * Get the student for this booking.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the designated mentor (teacher) for this booking.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'mentor_id');
    }

    /**
     * Get the speaking task associated with this booking.
     */
    public function speakingTask(): BelongsTo
    {
        return $this->belongsTo(SpeakingTask::class, 'speaking_task_id');
    }
}
