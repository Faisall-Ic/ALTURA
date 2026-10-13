<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tryout;
use App\Models\TryoutScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TryoutController extends Controller
{
    /**
     * Display student's Tryout dashboard & score analytics.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $scores = TryoutScore::with('tryout')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        $overallAverage = $scores->avg('average_score') ?? 0;
        $highestScore = $scores->max('average_score') ?? 0;

        $availableTryouts = Tryout::where('is_active', true)
            ->orderBy('date_start', 'desc')
            ->get();

        return view('siswa.tryout.index', compact(
            'student',
            'scores',
            'overallAverage',
            'highestScore',
            'availableTryouts'
        ));
    }

    /**
     * Display detailed score card and subtest analysis for a specific tryout.
     */
    public function show(Request $request, int|string $tryoutId): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $tryout = Tryout::findOrFail($tryoutId);

        $score = TryoutScore::where('student_id', $student->id)
            ->where('tryout_id', $tryout->id)
            ->firstOrFail();

        return view('siswa.tryout.show', compact('student', 'tryout', 'score'));
    }
}
