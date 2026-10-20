<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WacanaScript extends Model
{
    use HasFactory;

    protected $table = 'wacana_texts';

    protected $fillable = [
        'title',
        'content',
        'paragraph_count',
        'script_text',
    ];

    protected $casts = [
        'paragraph_count' => 'integer',
    ];

    public function getScriptTextAttribute(): ?string
    {
        return $this->attributes['content'] ?? null;
    }

    public function setScriptTextAttribute(?string $val): void
    {
        $this->attributes['content'] = $val;
    }
}
