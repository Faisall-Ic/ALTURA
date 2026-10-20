<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounselingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'counselor_user_id',
        'date_time',
        'room_or_link',
        'max_quota',
        'remaining_quota',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'max_quota' => 'integer',
        'remaining_quota' => 'integer',
    ];

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(CounselingBooking::class, 'slot_id');
    }
}
