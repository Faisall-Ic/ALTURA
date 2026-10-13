<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationSlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mentor_id',
        'topic',
        'date_time',
        'max_quota',
        'remaining_quota',
        'location_or_link',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_time' => 'datetime',
            'max_quota' => 'integer',
            'remaining_quota' => 'integer',
        ];
    }

    /**
     * Get the mentor (teacher) offering this slot.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'mentor_id');
    }

    /**
     * Get the bookings registered for this slot.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(ConsultationBooking::class, 'slot_id');
    }
}
