<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpeakingTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'title',
        'script_text',
        'minimum_pass_grade',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'minimum_pass_grade' => 'float',
        ];
    }

    /**
     * Get bookings associated with this speaking task.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(SpeakingBooking::class);
    }
}
