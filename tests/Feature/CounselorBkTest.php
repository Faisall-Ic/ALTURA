<?php

namespace Tests\Feature;

use App\Models\AttendanceFlag;
use App\Models\ConsultationBooking;
use App\Models\ConsultationSlot;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselorBkTest extends TestCase
{
    use RefreshDatabase;

    protected User $counselorUser;

    protected User $studentUser;

    protected Student $student;

    protected Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->counselorUser = User::role('guru_bk')->first();
        $this->student = Student::first();
        $this->studentUser = $this->student->user;
        $this->teacher = Teacher::first();
    }

    public function test_counselor_can_access_dashboard_with_health_matrix(): void
    {
        $response = $this->actingAs($this->counselorUser)->get(route('bk.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Guru BK');
        $response->assertSee('Health Matrix');
        $response->assertSee('Normal');
        $response->assertSee($this->studentUser->name);
    }

    public function test_counselor_can_view_flagging_inbox_and_filter(): void
    {
        // Ensure at least one open flag
        $flag = AttendanceFlag::firstOrCreate(
            ['student_id' => $this->student->id, 'flag_type' => 'late_series'],
            ['reason' => 'Test late series flag', 'status' => 'open']
        );

        $response = $this->actingAs($this->counselorUser)->get(route('bk.flagging.index'));

        $response->assertStatus(200);
        $response->assertSee('Kotak Flagging Siswa Berisiko');
        $response->assertSee($this->studentUser->name);
    }

    public function test_counselor_can_resolve_attendance_flag(): void
    {
        $flag = AttendanceFlag::create([
            'student_id' => $this->student->id,
            'flag_type' => 'late_series',
            'reason' => 'Testing resolution workflow',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->counselorUser)
            ->post(route('bk.flagging.resolve', $flag->id), [
                'status' => 'resolved',
                'resolution_notes' => 'Sudah dilakukan pemanggilan dan konseling tatap muka.',
            ]);

        $response->assertSessionHas('success');

        $flag->refresh();
        $this->assertEquals('resolved', $flag->status);
        $this->assertEquals($this->counselorUser->id, $flag->resolved_by_counselor_id);
        $this->assertNotNull($flag->resolved_at);
    }

    public function test_counselor_can_view_attendance_recap_and_target_recap(): void
    {
        $response1 = $this->actingAs($this->counselorUser)->get(route('bk.rekap.presensi'));
        $response1->assertStatus(200);
        $response1->assertSee('Rekapitulasi Presensi Akademik');

        $response2 = $this->actingAs($this->counselorUser)->get(route('bk.rekap.target'));
        $response2->assertStatus(200);
        $response2->assertSee('Rekapitulasi Target Bulanan Siswa');
    }

    public function test_counselor_can_manage_bk_consultation_slots_and_update_status(): void
    {
        // 1. Create Slot
        $response = $this->actingAs($this->counselorUser)->post(route('bk.konsultasi.store'), [
            'mentor_id' => $this->teacher->id,
            'topic' => 'Sesi Konsultasi Minat Bakat SNBP',
            'date_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'max_quota' => 4,
            'location_or_link' => 'Ruang BK Alto Lantai 2',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('consultation_slots', [
            'topic' => 'Sesi Konsultasi Minat Bakat SNBP',
        ]);

        $slot = ConsultationSlot::where('topic', 'Sesi Konsultasi Minat Bakat SNBP')->first();

        // 2. Booking on this slot
        $booking = ConsultationBooking::create([
            'slot_id' => $slot->id,
            'student_id' => $this->student->id,
            'parent_id' => User::role('orang_tua')->first()->id,
            'type' => 'parent_bk',
            'status' => 'confirmed',
            'notes' => 'Ingin konsultasi pemilihan kampus',
        ]);

        // 3. Update status
        $updateResponse = $this->actingAs($this->counselorUser)
            ->patch(route('bk.konsultasi.updateStatus', $booking->id), [
                'status' => 'attended',
                'notes' => 'Sesi telah terlaksana dengan baik.',
            ]);

        $updateResponse->assertSessionHas('success');
        $booking->refresh();
        $this->assertEquals('attended', $booking->status);
    }

    public function test_non_counselor_cannot_access_bk_portal(): void
    {
        $response = $this->actingAs($this->studentUser)->get(route('bk.dashboard'));
        $response->assertStatus(403);
    }
}
