<?php

namespace Tests\Feature;

use App\Models\ConsultationBooking;
use App\Models\ConsultationSlot;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationTest extends TestCase
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
     * Teacher can view consultation management page.
     */
    public function test_teacher_can_view_consultation_slots(): void
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.konsultasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Bimbingan');
    }

    /**
     * Teacher can create a new consultation slot.
     */
    public function test_teacher_can_create_consultation_slot(): void
    {
        $slotTime = Carbon::now()->addDays(3)->setHour(14)->setMinute(0)->setSecond(0);

        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.konsultasi.store'), [
                'topic' => 'Bedah Soal Penalaran Matematika Bab Statistika',
                'date_time' => $slotTime->toDateTimeString(),
                'max_quota' => 6,
                'location_or_link' => 'Ruang Diskusi C3',
            ]);

        $response->assertRedirect(route('guru.konsultasi.index'));

        $this->assertDatabaseHas('consultation_slots', [
            'mentor_id' => $this->teacher->id,
            'topic' => 'Bedah Soal Penalaran Matematika Bab Statistika',
            'max_quota' => 6,
            'remaining_quota' => 6,
        ]);
    }

    /**
     * Student can view consultation booking index.
     */
    public function test_student_can_view_consultation_index(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.konsultasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Konsultasi Mapel');
    }

    /**
     * Student can book open slot and quota decrements atomically.
     */
    public function test_student_can_book_open_slot(): void
    {
        $slot = ConsultationSlot::create([
            'mentor_id' => $this->teacher->id,
            'topic' => 'Klinik Soal Kimia Lanjutan',
            'date_time' => Carbon::now()->addDays(5),
            'max_quota' => 5,
            'remaining_quota' => 5,
            'location_or_link' => 'Ruang Alto',
        ]);

        $response = $this->actingAs($this->studentUser)
            ->post(route('siswa.konsultasi.book', $slot->id), [
                'notes' => 'Tolong bahas konsep ikatan kovalen',
            ]);

        $response->assertRedirect(route('siswa.konsultasi.index'));

        $slot->refresh();
        $this->assertEquals(4, $slot->remaining_quota);

        $this->assertDatabaseHas('consultation_bookings', [
            'slot_id' => $slot->id,
            'student_id' => $this->student->id,
            'status' => 'confirmed',
            'notes' => 'Tolong bahas konsep ikatan kovalen',
        ]);
    }

    /**
     * Student cannot book the same slot twice.
     */
    public function test_student_cannot_double_book_same_slot(): void
    {
        $slot = ConsultationSlot::create([
            'mentor_id' => $this->teacher->id,
            'topic' => 'Klinik Fisika Mekanika',
            'date_time' => Carbon::now()->addDays(5),
            'max_quota' => 5,
            'remaining_quota' => 5,
            'location_or_link' => 'Ruang Alto',
        ]);

        // First booking
        $this->actingAs($this->studentUser)
            ->post(route('siswa.konsultasi.book', $slot->id));

        // Second booking attempt
        $response = $this->actingAs($this->studentUser)
            ->post(route('siswa.konsultasi.book', $slot->id));

        $response->assertSessionHas('error');
        $slot->refresh();
        $this->assertEquals(4, $slot->remaining_quota);
    }

    /**
     * Student can cancel their booking, which increments remaining quota.
     */
    public function test_student_can_cancel_booking(): void
    {
        $slot = ConsultationSlot::create([
            'mentor_id' => $this->teacher->id,
            'topic' => 'Klinik Biologi Sel',
            'date_time' => Carbon::now()->addDays(5),
            'max_quota' => 5,
            'remaining_quota' => 4,
            'location_or_link' => 'Ruang Alto',
        ]);

        $booking = ConsultationBooking::create([
            'slot_id' => $slot->id,
            'student_id' => $this->student->id,
            'type' => 'student_mapel',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->studentUser)
            ->delete(route('siswa.konsultasi.cancel', $booking->id));

        $response->assertRedirect(route('siswa.konsultasi.index'));

        $booking->refresh();
        $slot->refresh();

        $this->assertEquals('cancelled', $booking->status);
        $this->assertEquals(5, $slot->remaining_quota);
    }

    /**
     * Teacher can delete consultation slot.
     */
    public function test_teacher_can_delete_consultation_slot(): void
    {
        $slot = ConsultationSlot::create([
            'mentor_id' => $this->teacher->id,
            'topic' => 'Slot to Delete',
            'date_time' => Carbon::now()->addDays(6),
            'max_quota' => 2,
            'remaining_quota' => 2,
            'location_or_link' => 'Ruang Alto',
        ]);

        $response = $this->actingAs($this->teacherUser)
            ->delete(route('guru.konsultasi.destroy', $slot->id));

        $response->assertRedirect(route('guru.konsultasi.index'));

        $this->assertDatabaseMissing('consultation_slots', [
            'id' => $slot->id,
        ]);
    }
}
