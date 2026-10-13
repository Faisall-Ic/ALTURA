<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryoutScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'tryout_id',
        'subtest_tps',
        'subtest_indo',
        'subtest_inggris',
        'subtest_matematika',
        'average_score',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtest_tps' => 'float',
            'subtest_indo' => 'float',
            'subtest_inggris' => 'float',
            'subtest_matematika' => 'float',
            'average_score' => 'float',
        ];
    }

    /**
     * Get the student who owns this score.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the tryout package for this score.
     */
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class, 'tryout_id');
    }
}
