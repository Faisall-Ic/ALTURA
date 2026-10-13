<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceFlag;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tryout;
use App\Models\TryoutScore;
use App\Models\WaMessageLog;
use App\Services\StudentFlaggingService;
use App\Services\WhatsAppGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected Student $student;

    protected Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->student = Student::first();
        $this->teacher = Teacher::first();
    }

    public function test_wa_gateway_dispatches_attendance_notification_and_logs(): void
    {
        $gateway = app(WhatsAppGatewayService::class);

        $attendance = Attendance::create([
            'student_id' => $this->student->id,
            'date' => now()->toDateString(),
            'session_time' => '08:00:00',
            'status' => 'hadir',
            'scanned_by_teacher_id' => $this->teacher->id,
        ]);

        $log = $gateway->sendAttendanceNotification($this->student, $attendance, 95.0);

        $this->assertInstanceOf(WaMessageLog::class, $log);
        $this->assertEquals($this->student->parent_phone_number, $log->parent_phone_number);
        $this->assertEquals('attendance_checkin', $log->trigger_event);
        $this->assertEquals('sent', $log->status);
        $this->assertStringContainsString('HADIR', $log->message_payload);
        $this->assertStringContainsString('95%', $log->message_payload);
    }

    public function test_wa_gateway_dispatches_flag_alert_and_tryout_score(): void
    {
        $gateway = app(WhatsAppGatewayService::class);

        // 1. Flag Alert
        $flag = AttendanceFlag::create([
            'student_id' => $this->student->id,
            'flag_type' => 'late_series',
            'reason' => 'Terlambat 3 kali beruntun',
            'status' => 'open',
        ]);

        $flagLog = $gateway->sendFlagAlert($this->student, $flag);
        $this->assertEquals('critical_flag_alert', $flagLog->trigger_event);
        $this->assertStringContainsString('PEMBERITAHUAN PENTING ALTURA', $flagLog->message_payload);

        // 2. Tryout Score
        $tryout = Tryout::first();
        $score = TryoutScore::where('student_id', $this->student->id)->where('tryout_id', $tryout->id)->first();

        $scoreLog = $gateway->sendTryoutScoreCard($this->student, $score);
        $this->assertEquals('tryout_score_card', $scoreLog->trigger_event);
        $this->assertStringContainsString('HASIL TRYOUT SNBT', $scoreLog->message_payload);
    }

    public function test_student_flagging_service_detects_consecutive_lates(): void
    {
        // Clear prior attendances for clean test
        Attendance::where('student_id', $this->student->id)->delete();
        AttendanceFlag::where('student_id', $this->student->id)->delete();

        // Create 3 late attendances
        for ($i = 3; $i >= 1; $i--) {
            Attendance::create([
                'student_id' => $this->student->id,
                'date' => now()->subDays($i)->toDateString(),
                'session_time' => '07:45:00',
                'status' => 'terlambat',
                'scanned_by_teacher_id' => $this->teacher->id,
            ]);
        }

        $service = app(StudentFlaggingService::class);
        $flag = $service->checkConsecutiveLates($this->student);

        $this->assertNotNull($flag);
        $this->assertEquals('late_series', $flag->flag_type);
        $this->assertEquals('open', $flag->status);
    }

    public function test_student_flagging_service_detects_consecutive_alfas(): void
    {
        Attendance::where('student_id', $this->student->id)->delete();
        AttendanceFlag::where('student_id', $this->student->id)->delete();

        for ($i = 2; $i >= 1; $i--) {
            Attendance::create([
                'student_id' => $this->student->id,
                'date' => now()->subDays($i)->toDateString(),
                'session_time' => '07:30:00',
                'status' => 'alfa',
                'scanned_by_teacher_id' => $this->teacher->id,
            ]);
        }

        $service = app(StudentFlaggingService::class);
        $flag = $service->checkCriticalAbsences($this->student);

        $this->assertNotNull($flag);
        $this->assertEquals('absence_critical', $flag->flag_type);
    }

    public function test_scanner_checkin_triggers_whatsapp_notification_log(): void
    {
        $teacherUser = $this->teacher->user;

        // Clean today's attendance for student
        Attendance::where('student_id', $this->student->id)->whereDate('date', now()->toDateString())->delete();

        $initialLogsCount = WaMessageLog::where('parent_phone_number', $this->student->parent_phone_number)->count();

        $response = $this->actingAs($teacherUser)->postJson(route('guru.presensi.scan.checkin'), [
            'token' => $this->student->qr_identity_token,
            'status' => 'hadir',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $newLogsCount = WaMessageLog::where('parent_phone_number', $this->student->parent_phone_number)->count();
        $this->assertGreaterThan($initialLogsCount, $newLogsCount);
    }
}
