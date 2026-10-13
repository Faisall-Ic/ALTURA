<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\SpeakingBooking;
use App\Models\Student;
use App\Models\TryoutScore;
use App\Models\VocabQuizResult;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TargetRecapController extends Controller
{
    /**
     * Display monthly student target achievement recap.
     */
    public function index(Request $request, AttendanceCalculatorService $calculator): View
    {
        $classId = $request->query('class_id');

        $query = Student::with(['user', 'academicClass', 'major', 'mentor.user'])
            ->where('status_active', true);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $students = $query->get();

        $studentRecaps = [];
        foreach ($students as $student) {
            $attendanceStats = $calculator->calculate($student->id);

            // Vocab stats
            $passedModules = VocabQuizResult::where('student_id', $student->id)
                ->where('is_passed', true)
                ->count();
            $vocabAverage = VocabQuizResult::where('student_id', $student->id)->avg('score') ?? 0;

            // Speaking stats
            $speakingBooking = SpeakingBooking::where('student_id', $student->id)
                ->latest()
                ->first();

            // Tryout stats
            $latestTryout = TryoutScore::where('student_id', $student->id)
                ->latest()
                ->first();

            $studentRecaps[] = [
                'student' => $student,
                'attendance_percentage' => $attendanceStats['percentage'],
                'attendance_color' => $attendanceStats['color'],
                'vocab_passed_modules' => $passedModules,
                'vocab_average' => round($vocabAverage, 1),
                'speaking_status' => $speakingBooking?->status ?? 'belum_booking',
                'speaking_grade' => $speakingBooking?->grade,
                'tryout_average' => $latestTryout?->average_score ?? 0,
            ];
        }

        $classes = AcademicClass::orderBy('name')->get();

        return view('guru_bk.rekap.target', compact('studentRecaps', 'classes', 'classId'));
    }
}
