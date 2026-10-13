<?php

namespace App\Services;

use App\Models\SpeakingBooking;
use App\Models\Student;
use App\Models\TryoutScore;
use App\Models\VocabQuizResult;
use Illuminate\Contracts\View\View;

class PdfReportService
{
    public function __construct(
        protected AttendanceCalculatorService $calculator,
    ) {}

    /**
     * Prepare data and render official Tryout Score Report.
     */
    public function renderTryoutReport(TryoutScore $score): View
    {
        $score->loadMissing(['student.user', 'student.academicClass', 'student.major', 'student.mentor.user', 'tryout']);
        $student = $score->student;

        return view('reports.tryout-pdf', compact('score', 'student'));
    }

    /**
     * Prepare data and render comprehensive Academic Progress 360° Report.
     */
    public function renderAcademicProgressReport(Student $student): View
    {
        $student->loadMissing(['user', 'academicClass', 'major', 'mentor.user', 'counselor']);

        $attendanceStats = $this->calculator->calculate($student->id);

        $tryoutScores = TryoutScore::with('tryout')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        $passedVocabModules = VocabQuizResult::where('student_id', $student->id)
            ->where('is_passed', true)
            ->count();

        $latestSpeaking = SpeakingBooking::with('speakingTask')
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        return view('reports.progress-pdf', compact(
            'student',
            'attendanceStats',
            'tryoutScores',
            'passedVocabModules',
            'latestSpeaking'
        ));
    }
}
