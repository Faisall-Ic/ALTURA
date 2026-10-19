<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorytellingTheme extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'month',
        'year',
        'theme_name',
        'description',
    ];

    /**
     * Get the submissions for this storytelling theme.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(StorytellingSubmission::class, 'theme_id');
    }
}
