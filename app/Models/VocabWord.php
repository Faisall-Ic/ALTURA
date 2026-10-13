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
        'example_sentence',
    ];

    /**
     * Scope query to a specific module.
     */
    public function scopeByModule(Builder $query, int $moduleNumber): Builder
    {
        return $query->where('module_number', $moduleNumber);
    }
}
