<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectConsultationBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'student_id',
        'status',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(SubjectConsultationSlot::class, 'slot_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
