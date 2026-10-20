<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance; // 1. Tambahkan import model Attendance di sini
use App\Models\Major;
use App\Models\Student;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceReportController extends Controller
{
    /**
     * Display automated attendance recap reports with health classification.
     */
    public function index(Request $request, AttendanceCalculatorService $calculator): View
    {
        $classId = $request->query('class_id');
        $majorId = $request->query('major_id');
        $healthColor = $request->query('color');

        $query = Student::with(['user', 'academicClass', 'major', 'mentor.user'])
            ->where('status_active', true);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($majorId) {
            $query->where('major_id', $majorId);
        }

        $students = $query->get();

        $rows = [];
        $totalNormal = 0;
        $totalWarning = 0;
        $totalCritical = 0;

        foreach ($students as $student) {
            $stats = $calculator->calculate($student->id);

            if ($stats['color'] === 'green') {
                $totalNormal++;
            } elseif ($stats['color'] === 'yellow') {
                $totalWarning++;
            } else {
                $totalCritical++;
            }

            if ($healthColor && $stats['color'] !== $healthColor) {
                continue;
            }

            $rows[] = [
                'student' => $student,
                'stats' => $stats,
            ];
        }

        $classes = AcademicClass::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();

        return view('guru_bk.rekap.presensi', compact(
            'rows',
            'classes',
            'majors',
            'classId',
            'majorId',
            'healthColor',
            'totalNormal',
            'totalWarning',
            'totalCritical'
        ));
    } // 2. Tutup fungsi index() di sini

    /**
     * Display detail attendance history for a specific student.
     */
    public function show($studentId): View
{
    $student = Student::with(['user', 'academicClass', 'major'])->findOrFail($studentId);

    $attendances = Attendance::with(['scannedBy.user'])
        ->where('student_id', $studentId)
        ->latest()
        ->get();

    // Sesuaikan nama view di sini:
    return view('guru_bk.rekap.detail_presensi', compact('student', 'attendances'));
}
} // 4. Tutup kelas AttendanceReportController di sini