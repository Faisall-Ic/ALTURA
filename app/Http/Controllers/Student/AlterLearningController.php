<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AlterChapter;
use App\Models\AlterCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlterLearningController extends Controller
{
    /**
     * Display the One-Page Learning player (Plyr.js / YouTube Embed + Notebook Essay Problems).
     */
    public function player(Request $request, ?int $chapterId = null): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        // Find chapter
        if ($chapterId) {
            $currentChapter = AlterChapter::with(['course.chapters', 'problems'])->findOrFail($chapterId);
            $currentCourse = $currentChapter->course;
        } else {
            // Find first course accessible to student's major
            $currentCourse = AlterCourse::where('major_id', $student->major_id)
                ->orWhere('is_snbt_core', true)
                ->with(['chapters.problems'])
                ->first();

            $currentChapter = $currentCourse?->chapters->first();
        }

        // Available courses for quick switcher
        $courses = AlterCourse::where('major_id', $student->major_id)
            ->orWhere('is_snbt_core', true)
            ->with(['chapters'])
            ->get();

        $chapters = $currentCourse?->chapters ?? collect();

        return view('siswa.alter.player', compact('student', 'currentCourse', 'currentChapter', 'courses', 'chapters'));
    }
}
