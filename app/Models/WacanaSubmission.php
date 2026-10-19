<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WacanaSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'wacana_id',
        'status',
    ];

    /**
     * Get the student who submitted this wacana.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the wacana text for this submission.
     */
    public function wacanaText(): BelongsTo
    {
        return $this->belongsTo(WacanaText::class, 'wacana_id');
    }
}
