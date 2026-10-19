<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentGuardian extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'phone_number',
        'occupation',
        'address',
    ];

    /**
     * User account for this parent.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Students linked to this parent.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_id', 'student_id')
            ->withPivot('relationship_type')
            ->withTimestamps();
    }

    /**
     * Counseling bookings requested by this parent.
     */
    public function counselingBookings(): HasMany
    {
        return $this->hasMany(CounselingBooking::class, 'parent_id');
    }
}
