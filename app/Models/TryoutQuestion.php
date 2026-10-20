<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TryoutQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tryout_id',
        'subtest_code',
        'question_text',
        'options',
        'correct_answer',
        'explanation',
        'image_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    /**
     * Get the tryout package this question belongs to.
     */
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    /**
     * Get the student answers for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(TryoutAnswer::class, 'question_id');
    }
}
