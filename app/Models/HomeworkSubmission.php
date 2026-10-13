<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeworkSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_file_path',
        'student_note',
        'grade',
        'feedback',
        'submitted_at',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(HomeworkAssignment::class, 'assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
