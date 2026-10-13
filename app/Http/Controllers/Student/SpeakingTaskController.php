<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SpeakingTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpeakingTaskController extends Controller
{
    /**
     * Display digital task cards for Storytelling and Wacana scripts.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $storytellingTasks = SpeakingTask::where('type', 'storytelling')->get();
        $wacanaTasks = SpeakingTask::where('type', 'wacana')->get();

        return view('siswa.english.task-cards', compact('student', 'storytellingTasks', 'wacanaTasks'));
    }
}
