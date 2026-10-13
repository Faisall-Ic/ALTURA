<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationBooking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slot_id',
        'student_id',
        'parent_id',
        'type',
        'status',
        'notes',
    ];

    /**
     * Get the slot that was booked.
     */
    public function slot(): BelongsTo
    {
        return $this->belongsTo(ConsultationSlot::class, 'slot_id');
    }

    /**
     * Get the student who made the booking.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the parent user who made the booking.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
