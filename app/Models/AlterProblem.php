<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlterProblem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'chapter_id',
        'problem_number',
        'type_name',
        'problem_image_path',
    ];

    /**
     * Get the chapter this problem belongs to.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(AlterChapter::class, 'chapter_id');
    }
}
