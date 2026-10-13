<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlterChapter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_id',
        'chapter_number',
        'title',
        'youtube_video_id',
    ];

    /**
     * Get the course this chapter belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(AlterCourse::class, 'course_id');
    }

    /**
     * Get the essay problems for this chapter.
     */
    public function problems(): HasMany
    {
        return $this->hasMany(AlterProblem::class, 'chapter_id')->orderBy('problem_number');
    }
}
