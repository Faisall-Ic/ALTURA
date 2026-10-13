<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceSessionController extends Controller
{
    /**
     * Display the class attendance logs with date and status filters.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $date = $request->query('date', now()->toDateString());
        $status = $request->query('status');
        $classId = $request->query('class_id');

        $attendances = Attendance::with(['student.user', 'student.academicClass', 'student.major', 'scannedBy.user'])
            ->whereDate('date', $date)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($classId, function ($query, $classId) {
                $query->whereHas('student', fn ($q) => $q->where('class_id', $classId));
            })
            ->orderBy('session_time', 'desc')
            ->paginate(20)
            ->withQueryString();

        $classes = AcademicClass::orderBy('name')->get();

        return view('guru.presensi.log-sesi', compact('attendances', 'classes', 'date', 'status', 'classId', 'teacher'));
    }

    /**
     * Update attendance record (manual adjustment).
     */
    public function update(Request $request, int $attendanceId): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:hadir,terlambat,sakit,izin,alfa'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $attendance = Attendance::findOrFail($attendanceId);
        $attendance->update($validated);

        return back()->with('success', "Status presensi {$attendance->student->user->name} berhasil diperbarui menjadi {$validated['status']}.");
    }
}
