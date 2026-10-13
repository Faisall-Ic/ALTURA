<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use App\Models\VocabWord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnglishVocabTest extends TestCase
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
     * Student can view English Vocab modules list.
     */
    public function test_student_can_view_vocab_modules(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.english.vocab.index'));

        $response->assertStatus(200);
        $response->assertSee('English Vocab Mastery');
        $response->assertSee('Modul Kosakata 1');
        $response->assertSee('Modul Kosakata 8');
    }

    /**
     * Student can open CBT quiz for a module.
     */
    public function test_student_can_take_vocab_quiz(): void
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('siswa.english.vocab.take', 1));

        $response->assertStatus(200);
        $response->assertSee('Kuis CBT Kosakata');
        $response->assertSee('PERTANYAAN #1 DARI 10');
    }

    /**
     * Student can submit CBT quiz and receive accurate auto-grading.
     */
    public function test_student_can_submit_and_pass_quiz(): void
    {
        $words = VocabWord::byModule(2)->limit(10)->get();

        // 8 correct answers, 2 incorrect -> 80% (>= 75% -> passed)
        $answers = [];
        foreach ($words as $index => $w) {
            $answers[$w->id] = ($index < 8) ? $w->indonesian_meaning : 'Jawaban Salah Sengaja';
        }

        $response = $this->actingAs($this->studentUser)
            ->post(route('siswa.english.vocab.submit', 2), [
                'answers' => $answers,
            ]);

        $response->assertRedirect(route('siswa.english.vocab.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('vocab_quiz_results', [
            'student_id' => $this->student->id,
            'module_number' => 2,
            'score' => 80.00,
            'passed' => true,
        ]);
    }

    /**
     * Failed quiz with score below 75 is recorded as not passed.
     */
    public function test_quiz_under_75_is_marked_failed(): void
    {
        $words = VocabWord::byModule(3)->limit(10)->get();

        // 5 correct answers, 5 incorrect -> 50% (< 75% -> failed)
        $answers = [];
        foreach ($words as $index => $w) {
            $answers[$w->id] = ($index < 5) ? $w->indonesian_meaning : 'Jawaban Salah';
        }

        $response = $this->actingAs($this->studentUser)
            ->post(route('siswa.english.vocab.submit', 3), [
                'answers' => $answers,
            ]);

        $response->assertRedirect(route('siswa.english.vocab.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('vocab_quiz_results', [
            'student_id' => $this->student->id,
            'module_number' => 3,
            'score' => 50.00,
            'passed' => false,
        ]);
    }
}
