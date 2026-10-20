<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabWord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'module_number',
        'english_word',
        'indonesian_meaning',
        'acceptable_answers',
        'example_sentence',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acceptable_answers' => 'array',
        ];
    }

    /**
     * Check if a submitted answer is acceptable (exact meaning or listed in acceptable_answers).
     */
    public function isAnswerAcceptable(string $answer): bool
    {
        $cleanAnswer = trim(strtolower($answer));
        if ($cleanAnswer === '') {
            return false;
        }

        if ($cleanAnswer === trim(strtolower((string) $this->english_word)) ||
            $cleanAnswer === trim(strtolower((string) $this->indonesian_meaning))) {
            return true;
        }

        $rawAcceptable = $this->acceptable_answers;
        if (is_string($rawAcceptable)) {
            $decoded = json_decode($rawAcceptable, true);
            $rawAcceptable = is_array($decoded) ? $decoded : [];
        } elseif (! is_array($rawAcceptable)) {
            $rawAcceptable = [];
        }

        $acceptable = array_map(fn ($item) => trim(strtolower((string) $item)), $rawAcceptable);

        return in_array($cleanAnswer, $acceptable, true);
    }

    /**
     * Scope query to a specific module.
     */
    public function scopeByModule(Builder $query, int $moduleNumber): Builder
    {
        return $query->where('module_number', $moduleNumber);
    }
}
