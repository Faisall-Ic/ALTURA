<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryoutAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'attempt_id',
        'question_id',
        'student_answer',
        'is_correct',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    /**
     * Get the attempt this answer belongs to.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(TryoutAttempt::class, 'attempt_id');
    }

    /**
     * Get the question this answer corresponds to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(TryoutQuestion::class, 'question_id');
    }
}
