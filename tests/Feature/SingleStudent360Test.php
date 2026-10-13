<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingleStudent360Test extends TestCase
{
    use RefreshDatabase;

    protected User $teacherUser;

    protected Teacher $teacher;

    protected User $studentUser;

    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->teacherUser = User::where('email', 'budi.guru@altura.test')->firstOrFail();
        $this->teacher = $this->teacherUser->teacher;

        $this->studentUser = User::where('email', 'siswa@altura.test')->firstOrFail();
        $this->student = $this->studentUser->student;
    }

    /**
     * Teacher can view single student 360° profile.
     */
    public function test_teacher_can_view_single_student_360_profile(): void
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.bimbingan.student.profile', $this->student->id));

        $response->assertStatus(200);
        $response->assertSee('Profil 360° Siswa');
        $response->assertSee($this->student->user->name);
        $response->assertSee($this->student->nisn);
        $response->assertSee('Tingkat Kehadiran');
        $response->assertSee('10 Log Presensi Terakhir');
        $response->assertSee('Riwayat Konsultasi');
    }

    /**
     * Guest cannot access 360 profile.
     */
    public function test_guest_cannot_view_student_360_profile(): void
    {
        $response = $this->get(route('guru.bimbingan.student.profile', $this->student->id));

        $response->assertRedirect(route('login'));
    }
}
