<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'date',
        'session_time',
        'status',
        'scanned_by_teacher_id',
        'remarks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * Get the student for this attendance record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teacher who performed the scan.
     */
    public function scannedBy(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'scanned_by_teacher_id');
    }

    /**
     * Alias for scannedBy relation.
     */
    public function scannedByTeacher(): BelongsTo
    {
        return $this->scannedBy();
    }
}
