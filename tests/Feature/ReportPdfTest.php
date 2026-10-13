<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\TryoutScore;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportPdfTest extends TestCase
{
    use RefreshDatabase;

    protected Student $student;

    protected User $studentUser;

    protected TryoutScore $score;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->student = Student::first();
        $this->studentUser = $this->student->user;
        $this->score = TryoutScore::where('student_id', $this->student->id)->first();
    }

    public function test_can_render_official_tryout_pdf_report(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('reports.tryout.pdf', $this->score->id));

        $response->assertStatus(200);
        $response->assertSee('BIMBEL ALTO');
        $response->assertSee('Hasil Ujian Tryout SNBT');
        $response->assertSee($this->studentUser->name);
        $response->assertSee($this->student->nisn);
        $response->assertSee(number_format($this->score->subtest_tps, 2, '.', ''));
        $response->assertSee(number_format($this->score->average_score, 2, '.', ''));
    }

    public function test_can_render_student_academic_progress_360_pdf(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('reports.progress.pdf', $this->student->id));

        $response->assertStatus(200);
        $response->assertSee('BIMBEL ALTO');
        $response->assertSee('Profil 360');
        $response->assertSee($this->studentUser->name);
        $response->assertSee($this->student->nisn);
        $response->assertSee('Rekapitulasi Presensi');
    }
}
