<?php

namespace Tests\Feature;

use App\Models\SpeakingBooking;
use App\Models\SpeakingTask;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpeakingExamTest extends TestCase
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
     * Student can view Storytelling and Wacana scripts.
     */
    public function test_student_can_view_speaking_task_cards(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.english.tasks.index'));

        $response->assertStatus(200);
        $response->assertSee('Storytelling: The Legend of Rawa Pening');
        $response->assertSee('Wacana Akademik: Artificial Intelligence in High School Education');
    }

    /**
     * Student can book oral exam strictly with assigned mentor.
     */
    public function test_student_can_book_oral_exam_with_assigned_mentor(): void
    {
        $task = SpeakingTask::where('type', 'wacana')->firstOrFail();

        $response = $this->actingAs($this->studentUser)
            ->post(route('siswa.english.speaking.store'), [
                'speaking_task_id' => $task->id,
                'booking_date' => Carbon::now()->addDays(4)->toDateString(),
                'time_slot' => '16:00 - 16:30',
            ]);

        $response->assertRedirect(route('siswa.english.speaking.booking'));

        $this->assertDatabaseHas('speaking_bookings', [
            'student_id' => $this->student->id,
            'mentor_id' => $this->student->mentor_id,
            'speaking_task_id' => $task->id,
            'time_slot' => '16:00 - 16:30',
            'status' => 'pending',
        ]);
    }

    /**
     * Teacher can view oral rubric grading interface for their assigned mentee.
     */
    public function test_teacher_can_view_oral_rubric(): void
    {
        $booking = SpeakingBooking::where('mentor_id', $this->teacher->id)->firstOrFail();

        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.bimbingan.rubric.edit', $booking->id));

        $response->assertStatus(200);
        $response->assertSee('Rubrik Penilaian Ujian Lisan');
        $response->assertSee('Pronunciation & Intonation', false);
        $response->assertSee('Fluency & Pace', false);
    }

    /**
     * Teacher can submit 4-criteria rubric evaluation and calculate final score.
     */
    public function test_teacher_can_grade_speaking_booking(): void
    {
        $booking = SpeakingBooking::create([
            'student_id' => $this->student->id,
            'mentor_id' => $this->teacher->id,
            'speaking_task_id' => SpeakingTask::firstOrFail()->id,
            'booking_date' => Carbon::now()->toDateString(),
            'time_slot' => '15:30 - 16:00',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.bimbingan.rubric.store', $booking->id), [
                'rubric_pronunciation' => 24,
                'rubric_fluency' => 23,
                'rubric_vocabulary' => 22,
                'rubric_comprehension' => 25,
                'rubric_notes' => 'Penguasaan naskah sangat prima dan percaya diri.',
            ]);

        $response->assertRedirect(route('guru.bimbingan.student.profile', $this->student->id));

        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
        $this->assertEquals(94.00, $booking->grade);
        $this->assertEquals('Penguasaan naskah sangat prima dan percaya diri.', $booking->rubric_notes);
    }

    /**
     * Teacher can view mentee vocab and oral monitoring overview.
     */
    public function test_teacher_can_view_monitoring_vocab(): void
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.bimbingan.vocab.monitor'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring English Mastery Siswa Mentee');
        $response->assertSee($this->student->user->name);
    }
}
