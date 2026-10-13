<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use App\Services\AttendanceCalculatorService;
use App\Services\StudentFlaggingService;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScannerController extends Controller
{
    /**
     * Display the mobile-optimized Teacher Desk-side Scanner interface.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $today = now()->toDateString();
        $todayScansCount = Attendance::where('scanned_by_teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->count();

        return view('guru.presensi.scanner', compact('teacher', 'todayScansCount'));
    }

    /**
     * Check-in API endpoint called by HTML5 camera scanner.
     */
    public function checkIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'status' => ['nullable', 'in:hadir,terlambat'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $token = trim($validated['token']);
        $status = $validated['status'] ?? 'hadir';

        // Find student by qr_identity_token or raw NISN
        $student = Student::with(['user', 'academicClass', 'major'])
            ->where('qr_identity_token', $token)
            ->orWhere('nisn', $token)
            ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak dikenali atau token siswa tidak valid.',
            ], 404);
        }

        if (! $student->status_active) {
            return response()->json([
                'success' => false,
                'message' => "Peringatan: Siswa {$student->user->name} berstatus non-aktif.",
            ], 422);
        }

        $today = now()->toDateString();
        $sessionTime = now()->format('H:i:s');

        // Duplicate scan check on the same date
        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'already_scanned' => true,
                'message' => "Presensi ganda dicegah: {$student->user->name} sudah tercatat {$existing->status} hari ini pada pukul {$existing->session_time}.",
                'student' => [
                    'name' => $student->user->name,
                    'nisn' => $student->nisn,
                    'class' => $student->academicClass?->name,
                    'status' => $existing->status,
                ],
            ], 409);
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'session_time' => $sessionTime,
            'status' => $status,
            'scanned_by_teacher_id' => $teacher->id,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Evaluate automated flags and dispatch WhatsApp notification to parent
        $calculator = app(AttendanceCalculatorService::class);
        $stats = $calculator->calculate($student->id);

        $flaggingService = app(StudentFlaggingService::class);
        $flaggingService->evaluateStudent($student, sendNotification: true);

        $waGateway = app(WhatsAppGatewayService::class);
        $waGateway->sendAttendanceNotification($student, $attendance, $stats['percentage']);

        return response()->json([
            'success' => true,
            'message' => "Presensi {$student->user->name} ({$status}) berhasil dicatat.",
            'attendance' => [
                'id' => $attendance->id,
                'student_name' => $student->user->name,
                'nisn' => $student->nisn,
                'class' => $student->academicClass?->name,
                'major' => $student->major?->name,
                'status' => $status,
                'session_time' => substr($sessionTime, 0, 5),
            ],
        ]);
    }
}
