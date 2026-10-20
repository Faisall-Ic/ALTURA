<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorytellingSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'theme_id',
        'title',
        'script_text',
        'notes',
        'status',
    ];

    /**
     * Get the student who submitted this storytelling.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the theme of this storytelling submission.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(StorytellingTheme::class, 'theme_id');
    }
}
