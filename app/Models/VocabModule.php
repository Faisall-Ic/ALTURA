<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VocabModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_number',
        'title',
        'description',
        'target_word_count',
    ];

    protected $casts = [
        'module_number' => 'integer',
        'target_word_count' => 'integer',
    ];

    public function words(): HasMany
    {
        return $this->hasMany(VocabWord::class, 'module_number', 'module_number');
    }
}
