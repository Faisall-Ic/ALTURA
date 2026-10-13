<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceCalculatorService $calculator
    ) {}

    /**
     * Display the dynamic QR Identity code for the authenticated student.
     */
    public function qrIdentity(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student()->with(['major', 'academicClass', 'mentor.user'])->firstOrFail();

        $stats = $this->calculator->calculate($student->id);

        return view('siswa.presensi.qr-identity', compact('student', 'stats'));
    }

    /**
     * Display student attendance history log and health percentage.
     */
    public function riwayat(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student()->firstOrFail();

        $attendances = Attendance::where('student_id', $student->id)
            ->with('scannedBy.user')
            ->orderBy('date', 'desc')
            ->orderBy('session_time', 'desc')
            ->paginate(15);

        $stats = $this->calculator->calculate($student->id);

        return view('siswa.presensi.riwayat', compact('student', 'attendances', 'stats'));
    }
}
