<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tryout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'batch_name',
        'date_start',
        'date_end',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the subtests for this tryout.
     */
    public function subtests(): HasMany
    {
        return $this->hasMany(TryoutSubtest::class);
    }

    /**
     * Get the scores recorded for this tryout.
     */
    public function tryoutScores(): HasMany
    {
        return $this->hasMany(TryoutScore::class, 'tryout_id');
    }

    /**
     * Aliases for start_date and end_date.
     */
    public function getStartDateAttribute(): mixed
    {
        return $this->date_start;
    }

    public function getEndDateAttribute(): mixed
    {
        return $this->date_end;
    }
}
