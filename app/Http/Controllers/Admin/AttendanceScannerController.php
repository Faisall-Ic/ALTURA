<?php

namespace App\Http\Controllers\Admin;

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

class AttendanceScannerController extends Controller
{
    /**
     * Display the Front Desk Entrance QR Scanner interface.
     */
    public function index(Request $request): View
    {
        $today = now()->toDateString();
        $adminId = $request->user()?->id;

        $todayScansCount = Attendance::whereDate('date', $today)->count();

        $recentAttendances = Attendance::with(['student.user', 'student.academicClass'])
            ->whereDate('date', $today)
            ->latest('id')
            ->take(10)
            ->get();

        return view('admin.attendance_scanner', compact('todayScansCount', 'recentAttendances'));
    }

    /**
     * Process student QR code token scanned at Front Desk entrance.
     */
    public function checkIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'status' => ['nullable', 'in:hadir,terlambat'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var User $adminUser */
        $adminUser = $request->user();
        $token = trim($validated['token']);
        $status = $validated['status'] ?? 'hadir';
        $today = now()->toDateString();
        $timeIn = now()->format('H:i:s');

        // Locate student by QR identity token, QR code token, or NISN
        $student = Student::with(['user', 'academicClass', 'major', 'parents'])
            ->where('qr_identity_token', $token)
            ->orWhere('qr_code_token', $token)
            ->orWhere('nisn', $token)
            ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR tidak dikenali. Pastikan kartu atau layar QR siswa valid.',
            ], 404);
        }

        // Prevent duplicate check-in on the same day
        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'already_scanned' => true,
                'message' => "Siswa {$student->user->name} sudah tercatat {$existing->status} hari ini pada pukul {$existing->time_in}.",
                'student' => [
                    'name' => $student->user->name,
                    'nisn' => $student->nisn ?? 'Belum ada NISN',
                    'class' => $student->academicClass?->name ?? 'Belum ada kelas',
                    'status' => $existing->status,
                    'time_in' => $existing->time_in,
                ],
            ], 409);
        }

        // Record attendance with scanned_by_admin_id (Rule 2)
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'time_in' => $timeIn,
            'session_time' => $timeIn,
            'status' => $status,
            'scanned_by_admin_id' => $adminUser->id,
            'remarks' => $validated['remarks'] ?? 'Presensi Masuk Front Desk',
        ]);

        // Evaluate automated flags and notify parent via WhatsApp Gateway
        $calculator = app(AttendanceCalculatorService::class);
        $stats = $calculator->calculate($student->id);

        $flaggingService = app(StudentFlaggingService::class);
        $flaggingService->evaluateStudent($student, sendNotification: true);

        $waGateway = app(WhatsAppGatewayService::class);
        $waGateway->sendAttendanceNotification($student, $attendance, $stats['percentage']);

        return response()->json([
            'success' => true,
            'message' => "Presensi masuk {$student->user->name} ({$status}) berhasil diverifikasi.",
            'attendance' => [
                'id' => $attendance->id,
                'student_name' => $student->user->name,
                'nisn' => $student->nisn ?? 'Tanpa NISN',
                'class' => $student->academicClass?->name ?? '-',
                'status' => $status,
                'time_in' => $timeIn,
                'date' => $today,
            ],
            'stats' => $stats,
        ]);
    }
}
