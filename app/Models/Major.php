<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'is_snbt_mandatory',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_snbt_mandatory' => 'boolean',
        ];
    }

    /**
     * Get the classes belonging to this major.
     */
    public function academicClasses(): HasMany
    {
        return $this->hasMany(AcademicClass::class);
    }

    /**
     * Get the students belonging to this major.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the ALTER courses belonging to this major.
     */
    public function alterCourses(): HasMany
    {
        return $this->hasMany(AlterCourse::class);
    }
}
