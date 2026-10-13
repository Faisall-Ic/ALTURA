<?php

namespace Tests\Feature;

use App\Models\AlterChapter;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlterLmsTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;

    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->studentUser = User::where('email', 'siswa@altura.test')->firstOrFail();
        $this->student = $this->studentUser->student;
    }

    /**
     * Enrolled student can view curriculum syllabus.
     */
    public function test_student_can_view_curriculum(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.alter.kurikulum'));

        $response->assertStatus(200);
        $response->assertSee('Silabus & Kurikulum ALTER LMS', false);
        $response->assertSee('Penalaran Matematika');
    }

    /**
     * Student can access One-Page Learning player with video and essay problems.
     */
    public function test_student_can_access_one_page_learning_player(): void
    {
        $chapter = AlterChapter::firstOrFail();

        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.alter.player', $chapter->id));

        $response->assertStatus(200);
        $response->assertSee('Ruang Belajar ALTER');
        $response->assertSee($chapter->title);
        $response->assertSee('Lembar Latihan Soal Uraian Mandiri');
        $response->assertSee('youtube-nocookie.com/embed/'.$chapter->youtube_video_id, false);
    }

    /**
     * Inactive student is blocked from ALTER player by EnsureActiveStudent.
     */
    public function test_inactive_student_is_blocked_from_alter(): void
    {
        $this->student->update(['status_active' => false]);

        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.alter.player'));

        $response->assertStatus(403);
    }
}
