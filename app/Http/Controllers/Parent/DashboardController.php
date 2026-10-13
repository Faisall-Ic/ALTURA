<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceFlag;
use App\Models\Student;
use App\Models\TryoutScore;
use App\Models\User;
use App\Models\VocabQuizResult;
use App\Models\WaMessageLog;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the parent portal dashboard.
     */
    public function index(Request $request, AttendanceCalculatorService $calculator): View
    {
        /** @var User $user */
        $user = $request->user();

        // Retrieve parent's children (or fallback to student linked by phone or first available)
        $students = $user->children()->with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])->get();
        if ($students->isEmpty()) {
            $students = Student::with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])->take(1)->get();
        }

        $studentData = [];
        foreach ($students as $student) {
            $attendanceStats = $calculator->calculate($student->id);

            $recentAttendances = Attendance::where('student_id', $student->id)
                ->orderBy('date', 'desc')
                ->take(5)
                ->get();

            $latestTryout = TryoutScore::with('tryout')
                ->where('student_id', $student->id)
                ->latest()
                ->first();

            $passedVocabCount = VocabQuizResult::where('student_id', $student->id)
                ->where('is_passed', true)
                ->count();

            $activeFlags = AttendanceFlag::where('student_id', $student->id)
                ->whereIn('status', ['open', 'in_progress'])
                ->get();

            $studentData[] = [
                'student' => $student,
                'attendance_stats' => $attendanceStats,
                'recent_attendances' => $recentAttendances,
                'latest_tryout' => $latestTryout,
                'passed_vocab_count' => $passedVocabCount,
                'active_flags' => $activeFlags,
            ];
        }

        $phone = $students->first()?->parent_phone_number ?? '6281234567890';
        $recentWaLogs = WaMessageLog::where('parent_phone_number', $phone)
            ->latest()
            ->take(4)
            ->get();

        return view('orang_tua.dashboard', compact('user', 'studentData', 'recentWaLogs'));
    }
}
