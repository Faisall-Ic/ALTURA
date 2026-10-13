<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\AttendanceCalculatorService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;

    protected Student $student;

    protected User $teacherUser;

    protected Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->studentUser = User::where('email', 'siswa@altura.test')->firstOrFail();
        $this->student = $this->studentUser->student;

        $this->teacherUser = User::where('email', 'budi.guru@altura.test')->firstOrFail();
        $this->teacher = $this->teacherUser->teacher;
    }

    /**
     * Student can view their QR Identity page.
     */
    public function test_student_can_view_qr_identity(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.presensi.qr'));

        $response->assertStatus(200);
        $response->assertSee($this->student->qr_identity_token);
        $response->assertSee($this->student->user->name);
    }

    /**
     * Student can view their attendance history.
     */
    public function test_student_can_view_attendance_history(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.presensi.riwayat'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Presensi Belajar');
        $response->assertSee('Indeks Kehadiran Akumulatif');
    }

    /**
     * Teacher can view desk-side QR scanner interface.
     */
    public function test_teacher_can_view_scanner(): void
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.presensi.scanner'));

        $response->assertStatus(200);
        $response->assertSee('Desk-side QR Scanner Presensi');
    }

    /**
     * Teacher can check in student using QR token via JSON API.
     */
    public function test_teacher_can_check_in_student_via_qr_token(): void
    {
        // Delete any existing attendance today for this student
        Attendance::where('student_id', $this->student->id)
            ->where('date', now()->toDateString())
            ->delete();

        $response = $this->actingAs($this->teacherUser)
            ->postJson(route('guru.presensi.scan.checkin'), [
                'token' => $this->student->qr_identity_token,
                'status' => 'hadir',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'attendance' => [
                'student_name' => $this->student->user->name,
                'status' => 'hadir',
            ],
        ]);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $this->student->id,
            'status' => 'hadir',
            'scanned_by_teacher_id' => $this->teacher->id,
        ]);
    }

    /**
     * Duplicate scan on same date returns 409 Conflict.
     */
    public function test_duplicate_scan_on_same_date_returns_conflict(): void
    {
        // First scan
        Attendance::where('student_id', $this->student->id)
            ->where('date', now()->toDateString())
            ->delete();

        $this->actingAs($this->teacherUser)
            ->postJson(route('guru.presensi.scan.checkin'), [
                'token' => $this->student->qr_identity_token,
                'status' => 'hadir',
            ]);

        // Second scan should be rejected
        $response = $this->actingAs($this->teacherUser)
            ->postJson(route('guru.presensi.scan.checkin'), [
                'token' => $this->student->qr_identity_token,
                'status' => 'hadir',
            ]);

        $response->assertStatus(409);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * AttendanceCalculatorService correctly computes percentages and health tiers.
     */
    public function test_attendance_calculator_service_tiers(): void
    {
        /** @var AttendanceCalculatorService $service */
        $service = app(AttendanceCalculatorService::class);

        // Clear existing attendances for isolation
        Attendance::where('student_id', $this->student->id)->delete();

        // 10 sessions total: 9 hadir, 1 alfa -> 90% (Normal / Green)
        for ($i = 0; $i < 9; $i++) {
            Attendance::create([
                'student_id' => $this->student->id,
                'date' => Carbon::now()->subDays($i + 1)->toDateString(),
                'session_time' => '16:00:00',
                'status' => 'hadir',
                'scanned_by_teacher_id' => $this->teacher->id,
            ]);
        }
        Attendance::create([
            'student_id' => $this->student->id,
            'date' => Carbon::now()->subDays(10)->toDateString(),
            'session_time' => '16:00:00',
            'status' => 'alfa',
            'scanned_by_teacher_id' => $this->teacher->id,
        ]);

        $stats = $service->calculate($this->student->id);
        $this->assertEquals(10, $stats['total']);
        $this->assertEquals(9, $stats['hadir']);
        $this->assertEquals(90.0, $stats['percentage']);
        $this->assertEquals('green', $stats['color']);
        $this->assertEquals('Normal', $stats['status_label']);

        // Add 2 more alfas -> 9/12 = 75.0% (Warning / Yellow)
        for ($i = 11; $i <= 12; $i++) {
            Attendance::create([
                'student_id' => $this->student->id,
                'date' => Carbon::now()->subDays($i)->toDateString(),
                'session_time' => '16:00:00',
                'status' => 'alfa',
                'scanned_by_teacher_id' => $this->teacher->id,
            ]);
        }
        $stats2 = $service->calculate($this->student->id);
        $this->assertEquals(75.0, $stats2['percentage']);
        $this->assertEquals('yellow', $stats2['color']);
        $this->assertEquals('Warning', $stats2['status_label']);

        // Add 1 more alfa -> 9/13 = 69.2% (Critical / Red)
        Attendance::create([
            'student_id' => $this->student->id,
            'date' => Carbon::now()->subDays(13)->toDateString(),
            'session_time' => '16:00:00',
            'status' => 'alfa',
            'scanned_by_teacher_id' => $this->teacher->id,
        ]);
        $stats3 = $service->calculate($this->student->id);
        $this->assertLessThan(75.0, $stats3['percentage']);
        $this->assertEquals('red', $stats3['color']);
        $this->assertEquals('Kritis', $stats3['status_label']);
    }

    /**
     * Teacher can adjust attendance record manually.
     */
    public function test_teacher_can_adjust_attendance_record(): void
    {
        $attendance = Attendance::where('student_id', $this->student->id)->firstOrFail();

        $response = $this->actingAs($this->teacherUser)
            ->put(route('guru.presensi.update', $attendance->id), [
                'status' => 'izin',
                'remarks' => 'Penyesuaian: Siswa melampirkan surat izin dokter',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => 'izin',
            'remarks' => 'Penyesuaian: Siswa melampirkan surat izin dokter',
        ]);
    }
}
