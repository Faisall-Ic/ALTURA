<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorytellingScript extends Model
{
    use HasFactory;

    protected $table = 'storytelling_themes';

    protected $fillable = [
        'month',
        'year',
        'theme_name',
        'description',
        'title',
        'script_text',
        'target_duration_minutes',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->attributes['theme_name'] ?? $this->attributes['title'] ?? null;
    }

    public function setTitleAttribute(?string $val): void
    {
        $this->attributes['theme_name'] = $val;
        if (! isset($this->attributes['month'])) {
            $this->attributes['month'] = now()->month;
        }
        if (! isset($this->attributes['year'])) {
            $this->attributes['year'] = now()->year;
        }
    }

    public function getScriptTextAttribute(): ?string
    {
        return $this->attributes['description'] ?? $this->attributes['script_text'] ?? null;
    }

    public function setScriptTextAttribute(?string $val): void
    {
        $this->attributes['description'] = $val;
    }
}
