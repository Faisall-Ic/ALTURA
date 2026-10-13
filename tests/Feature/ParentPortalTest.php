<?php

namespace Tests\Feature;

use App\Models\ConsultationSlot;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $parentUser;

    protected Student $student;

    protected Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->parentUser = User::role('orang_tua')->first();
        $this->student = Student::first();
        $this->teacher = Teacher::first();
    }

    public function test_parent_can_access_dashboard_and_see_child_data(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('ortu.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Portal Orang Tua');
        $response->assertSee($this->student->user->name);
        $response->assertSee($this->student->nisn);
    }

    public function test_parent_can_view_child_attendance_history(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('ortu.rekap.presensi'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Kehadiran:');
        $response->assertSee($this->student->user->name);
    }

    public function test_parent_can_view_child_academic_results_and_tryouts(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('ortu.rekap.hasil'));

        $response->assertStatus(200);
        $response->assertSee('Hasil Belajar:');
        $response->assertSee('Tryout SNBT Alto');
        $response->assertSee('Vocab Mastery');
    }

    public function test_parent_can_book_bk_counseling_session(): void
    {
        $slot = ConsultationSlot::create([
            'mentor_id' => $this->teacher->id,
            'topic' => 'Konsultasi Belajar dengan Wali Murid',
            'date_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'max_quota' => 2,
            'remaining_quota' => 2,
            'location_or_link' => 'Ruang Konseling Alto',
        ]);

        $response = $this->actingAs($this->parentUser)->post(route('ortu.konsultasi.store'), [
            'slot_id' => $slot->id,
            'student_id' => $this->student->id,
            'notes' => 'Ingin konsultasi pemilihan kampus dan jurusan untuk ananda.',
        ]);

        $response->assertRedirect(route('ortu.konsultasi.booking'));
        $response->assertSessionHas('success');

        $slot->refresh();
        $this->assertEquals(1, $slot->remaining_quota);

        $this->assertDatabaseHas('consultation_bookings', [
            'slot_id' => $slot->id,
            'parent_id' => $this->parentUser->id,
            'student_id' => $this->student->id,
            'type' => 'parent_bk',
            'status' => 'confirmed',
        ]);
    }

    public function test_parent_cannot_book_when_slot_quota_is_exhausted(): void
    {
        $slot = ConsultationSlot::create([
            'mentor_id' => $this->teacher->id,
            'topic' => 'Sesi Penuh',
            'date_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'max_quota' => 1,
            'remaining_quota' => 0,
            'location_or_link' => 'Ruang BK',
        ]);

        $response = $this->actingAs($this->parentUser)->post(route('ortu.konsultasi.store'), [
            'slot_id' => $slot->id,
            'student_id' => $this->student->id,
            'notes' => 'Mencoba mendaftar pada sesi penuh',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_parent_can_view_mentor_contact_page(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('ortu.kontak'));

        $response->assertStatus(200);
        $response->assertSee('Hubungi Tim Pembimbing Ananda');
        $response->assertSee('WhatsApp');
    }

    public function test_non_parent_cannot_access_parent_portal(): void
    {
        $studentUser = $this->student->user;
        $response = $this->actingAs($studentUser)->get(route('ortu.dashboard'));

        $response->assertStatus(403);
    }
}
