<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ConsultationBooking;
use App\Models\Student;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SingleStudentProfileController extends Controller
{
    public function __construct(
        protected AttendanceCalculatorService $calculator
    ) {}

    /**
     * Display Single Student 360° Profile (Profil 360° Siswa).
     */
    public function show(Request $request, int $studentId): View
    {
        $student = Student::with(['user', 'major', 'academicClass', 'mentor.user', 'counselor'])
            ->findOrFail($studentId);

        $stats = $this->calculator->calculate($student->id);

        $recentAttendances = Attendance::where('student_id', $student->id)
            ->with('scannedBy.user')
            ->orderBy('date', 'desc')
            ->orderBy('session_time', 'desc')
            ->limit(10)
            ->get();

        $consultationBookings = ConsultationBooking::where('student_id', $student->id)
            ->with(['slot.mentor.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('guru.bimbingan.profil-360', compact('student', 'stats', 'recentAttendances', 'consultationBookings'));
    }
}
