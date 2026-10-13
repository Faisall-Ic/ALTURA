<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Tryout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTryoutAndProfileTest extends TestCase
{
    use RefreshDatabase;

    protected Student $student;

    protected User $studentUser;

    protected Tryout $tryout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->student = Student::first();
        $this->studentUser = $this->student->user;
        $this->tryout = Tryout::first();
    }

    public function test_student_can_view_tryout_overview_page(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.tryout.index'));

        $response->assertStatus(200);
        $response->assertSee('Evaluasi &amp; Nilai Tryout SNBT', escape: false);
        $response->assertSee($this->tryout->title);
    }

    public function test_student_can_view_specific_tryout_score_card(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.tryout.show', $this->tryout->id));

        $response->assertStatus(200);
        $response->assertSee($this->tryout->title);
        $response->assertSee('Tes Potensi Skolastik');
        $response->assertSee('Literasi B. Indonesia');
        $response->assertSee('Unduh Sertifikat PDF');
    }

    public function test_student_can_view_integrated_academic_profile(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.profil'));

        $response->assertStatus(200);
        $response->assertSee('Profil Akademik Terpadu');
        $response->assertSee($this->studentUser->name);
        $response->assertSee($this->student->nisn);
        $response->assertSee($this->student->academicClass?->name);
        $response->assertSee('Unduh Rapor 360');
    }
}
