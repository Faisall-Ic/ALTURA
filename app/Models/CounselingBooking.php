<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselingBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'student_id',
        'parent_id',
        'booking_type',
        'notes',
        'status',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(CounselingSlot::class, 'slot_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentGuardian::class, 'parent_id');
    }
}
