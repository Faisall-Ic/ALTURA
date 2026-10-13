<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VocabMonitoringController extends Controller
{
    /**
     * Display mentee student vocab quiz progress and oral speaking completion.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $mentees = Student::where('mentor_id', $teacher->id)
            ->with(['user', 'academicClass', 'major', 'vocabQuizResults', 'speakingBookings.speakingTask'])
            ->get();

        return view('guru.bimbingan.monitoring-vocab', compact('teacher', 'mentees'));
    }
}
