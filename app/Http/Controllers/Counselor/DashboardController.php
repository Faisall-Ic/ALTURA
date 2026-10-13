<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceFlag;
use App\Models\ConsultationBooking;
use App\Models\Student;
use App\Models\User;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the counselor (BK) dashboard with real-time health matrix.
     */
    public function index(Request $request, AttendanceCalculatorService $calculator): View
    {
        /** @var User $user */
        $user = $request->user();

        $students = Student::with(['user', 'academicClass', 'major', 'mentor.user'])
            ->where('status_active', true)
            ->get();

        $greenCount = 0;
        $yellowCount = 0;
        $redCount = 0;

        $studentStats = [];
        foreach ($students as $student) {
            $stats = $calculator->calculate($student->id);
            $studentStats[$student->id] = $stats;

            if ($stats['color'] === 'green') {
                $greenCount++;
            } elseif ($stats['color'] === 'yellow') {
                $yellowCount++;
            } else {
                $redCount++;
            }
        }

        $openFlags = AttendanceFlag::with(['student.user', 'student.academicClass', 'student.mentor.user'])
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->take(8)
            ->get();

        $openFlagsCount = AttendanceFlag::where('status', 'open')->count();
        $inProgressFlagsCount = AttendanceFlag::where('status', 'in_progress')->count();
        $resolvedFlagsCount = AttendanceFlag::where('status', 'resolved')->count();

        $upcomingBkConsultations = ConsultationBooking::with(['slot', 'student.user', 'parent'])
            ->where('type', 'parent_bk')
            ->latest()
            ->take(5)
            ->get();

        return view('guru_bk.dashboard', compact(
            'user',
            'students',
            'studentStats',
            'greenCount',
            'yellowCount',
            'redCount',
            'openFlags',
            'openFlagsCount',
            'inProgressFlagsCount',
            'resolvedFlagsCount',
            'upcomingBkConsultations'
        ));
    }
}
