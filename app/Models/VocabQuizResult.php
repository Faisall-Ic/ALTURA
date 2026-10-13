<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VocabQuizResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'module_number',
        'score',
        'passed',
        'completed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'float',
            'passed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the student who took this quiz.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
