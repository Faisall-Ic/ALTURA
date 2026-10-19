<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AlterCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurriculumController extends Controller
{
    /**
     * Display the filtered curriculum syllabus for student's major and SNBT track.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student()->with(['academicClass', 'major'])->first();

        $isTkaActive = $student?->academicClass?->is_tka_active ?? true;

        $coursesQuery = AlterCourse::with(['subject', 'chapters.problems'])
            ->where(function ($q) use ($student) {
                if ($student) {
                    $q->where('major_id', $student->major_id)
                        ->orWhere('is_snbt_core', true);
                } else {
                    $q->where('is_snbt_core', true);
                }
            });

        // Business Rule: When is_tka_active = false for Grade 12 classes, hide TKA subjects
        if (! $isTkaActive) {
            $coursesQuery->where(function ($q) {
                $q->where('is_snbt_core', true)
                    ->orWhereHas('subject', function ($sq) {
                        $sq->where('is_tka', false);
                    });
            });
        }

        $courses = $coursesQuery->get();

        return view('siswa.alter.kurikulum', compact('student', 'courses', 'isTkaActive'));
    }
}
