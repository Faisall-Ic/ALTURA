<?php

namespace Tests\Feature;

use App\Models\Tryout;
use App\Models\TryoutQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class AdminTryoutQuestionTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected Tryout $tryout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@altura.test')->firstOrFail();
        $this->tryout = Tryout::firstOrFail();
    }

    /**
     * Admin can view question management page for a tryout.
     */
    public function test_admin_can_view_questions_management(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.tryouts.questions.index', $this->tryout->id));

        $response->assertStatus(200);
        $response->assertSee('Kelola Soal');
        $response->assertSee('Tambah Soal Manual');
        $response->assertSee('Import Soal via Excel');
        $response->assertSee($this->tryout->title);
    }

    /**
     * Admin can create a question manually.
     */
    public function test_admin_can_create_question_manually(): void
    {
        $payload = [
            'subtest_code' => 'PM',
            'question_text' => 'Berapakah nilai dari 2^5 + 3^3?',
            'image_path' => null,
            'option_a' => '59',
            'option_b' => '58',
            'option_c' => '60',
            'option_d' => '64',
            'option_e' => '56',
            'correct_answer' => 'A',
            'explanation' => '2^5 = 32, 3^3 = 27, 32 + 27 = 59.',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.tryouts.questions.store', $this->tryout->id), $payload);

        $response->assertRedirect(route('admin.tryouts.questions.index', $this->tryout->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tryout_questions', [
            'tryout_id' => $this->tryout->id,
            'subtest_code' => 'PM',
            'correct_answer' => 'A',
        ]);
    }

    /**
     * Admin can update an existing question.
     */
    public function test_admin_can_update_question(): void
    {
        $question = TryoutQuestion::create([
            'tryout_id' => $this->tryout->id,
            'subtest_code' => 'TPS',
            'question_text' => 'Soal awal',
            'options' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4', 'E' => '5'],
            'correct_answer' => 'B',
            'explanation' => 'Penjelasan awal',
        ]);

        $updatePayload = [
            'subtest_code' => 'PU',
            'question_text' => 'Soal terupdate dengan teks baru',
            'image_path' => null,
            'option_a' => 'Alpha',
            'option_b' => 'Beta',
            'option_c' => 'Gamma',
            'option_d' => 'Delta',
            'option_e' => 'Epsilon',
            'correct_answer' => 'C',
            'explanation' => 'Penjelasan terupdate',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.tryouts.questions.update', [$this->tryout->id, $question->id]), $updatePayload);

        $response->assertRedirect(route('admin.tryouts.questions.index', $this->tryout->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tryout_questions', [
            'id' => $question->id,
            'subtest_code' => 'PU',
            'correct_answer' => 'C',
        ]);
    }

    /**
     * Admin can delete a question.
     */
    public function test_admin_can_delete_question(): void
    {
        $question = TryoutQuestion::create([
            'tryout_id' => $this->tryout->id,
            'subtest_code' => 'LBI',
            'question_text' => 'Soal yang akan dihapus',
            'options' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4', 'E' => '5'],
            'correct_answer' => 'A',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.tryouts.questions.destroy', [$this->tryout->id, $question->id]));

        $response->assertRedirect(route('admin.tryouts.questions.index', $this->tryout->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('tryout_questions', [
            'id' => $question->id,
        ]);
    }

    /**
     * Admin can trigger Excel import for tryout questions.
     */
    public function test_admin_can_import_questions_via_excel(): void
    {
        Excel::fake();

        $file = UploadedFile::fake()->create('bank_soal_snbt.xlsx', 100);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.tryouts.questions.import', $this->tryout->id), [
                'excel_file' => $file,
            ]);

        $response->assertRedirect(route('admin.tryouts.questions.index', $this->tryout->id));
        $response->assertSessionHas('success');

        Excel::assertImported('bank_soal_snbt.xlsx');
    }
}
