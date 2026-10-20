<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TryoutAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'tryout_id',
        'started_at',
        'submitted_at',
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
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Get the student taking this attempt.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the tryout package for this attempt.
     */
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    /**
     * Get the question answers submitted in this attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(TryoutAnswer::class, 'attempt_id');
    }
}
