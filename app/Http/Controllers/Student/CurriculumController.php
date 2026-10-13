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
        $student = $user->student;

        $courses = AlterCourse::where('major_id', $student->major_id)
            ->orWhere('is_snbt_core', true)
            ->with(['chapters.problems'])
            ->get();

        return view('siswa.alter.kurikulum', compact('student', 'courses'));
    }
}
