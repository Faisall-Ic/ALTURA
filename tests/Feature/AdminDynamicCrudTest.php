<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDynamicCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected Major $major;

    protected AcademicClass $class;

    protected Teacher $teacher;

    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->adminUser = User::factory()->create(['name' => 'Admin Test', 'email' => 'admin@test.com']);
        $this->adminUser->syncRoles(['admin']);

        $this->major = Major::create(['name' => 'MIPA', 'code' => 'MIPA', 'description' => 'Matematika dan Ilmu Pengetahuan Alam']);
        $this->class = AcademicClass::create([
            'major_id' => $this->major->id,
            'name' => '12 MIPA 1',
            'code' => '12-MIPA-01',
            'grade_level' => 12,
            'is_tka_active' => true,
        ]);

        $teacherUser = User::factory()->create(['name' => 'Guru Budi']);
        $teacherUser->syncRoles(['guru']);
        $this->teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'nip' => '19850101',
            'specialization_subject' => 'Matematika',
        ]);

        $this->subject = Subject::create([
            'name' => 'Penalaran Matematika',
            'code' => 'PM',
            'is_tka' => false,
            'description' => 'Subtes SNBT',
        ]);
    }

    public function test_admin_can_manage_subjects(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.mata-pelajaran.index'));
        $response->assertStatus(200);

        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.mata-pelajaran.store'), [
            'name' => 'Fisika Terapan',
            'code' => 'FIS',
            'is_tka' => 1,
            'description' => 'Fisika TKA Peminatan',
        ]);
        $storeResponse->assertRedirect(route('admin.mata-pelajaran.index'));

        $this->assertDatabaseHas('subjects', ['code' => 'FIS', 'is_tka' => true]);
    }

    public function test_admin_can_manage_lesson_schedules(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.jadwal.index'));
        $response->assertStatus(200);

        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.jadwal.store'), [
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room' => 'Lab 1',
            'day_of_week' => 'Senin',
            'time_start' => '08:00',
            'time_end' => '09:30',
            'note' => 'Pendalaman materi',
        ]);
        $storeResponse->assertRedirect(route('admin.jadwal.index'));

        $this->assertDatabaseHas('lesson_schedules', [
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'room' => 'Lab 1',
        ]);
    }

    public function test_admin_can_manage_homework_assignments(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.tugas.index'));
        $response->assertStatus(200);

        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.tugas.store'), [
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Matriks',
            'instructions' => 'Kerjakan soal 1-10',
            'due_date' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ]);
        $storeResponse->assertRedirect(route('admin.tugas.index'));

        $this->assertDatabaseHas('homework_assignments', [
            'title' => 'Tugas Matriks',
            'class_id' => $this->class->id,
        ]);
    }

    public function test_admin_can_manage_alter_lms(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.alter.index'));
        $response->assertStatus(200);

        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.alter.store'), [
            'title' => 'ALTER PM Masterclass',
            'subject_id' => $this->subject->id,
            'description' => 'Kursus lengkap penalaran matematika',
            'is_snbt_core' => true,
        ]);
        $storeResponse->assertSessionHasNoErrors();
        $storeResponse->assertRedirect();

        $this->assertDatabaseHas('alter_courses', [
            'title' => 'ALTER PM Masterclass',
            'subject_id' => $this->subject->id,
        ]);
    }

    public function test_admin_can_manage_english_mastery(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.english.index'));
        $response->assertStatus(200);

        $vocabResponse = $this->actingAs($this->adminUser)->post(route('admin.english.vocab.store'), [
            'module_number' => 1,
            'english_word' => 'Tenacity',
            'indonesian_meaning' => 'Kegigihan',
            'example_sentence' => 'He had the tenacity to succeed.',
        ]);
        $vocabResponse->assertRedirect(route('admin.english.index', ['tab' => 'vocab', 'module' => 1]));

        $this->assertDatabaseHas('vocab_words', ['english_word' => 'Tenacity']);
    }

    public function test_admin_can_manage_subject_consultations_and_bk_counseling_separately(): void
    {
        // 1. Subject Consultation (Tentoring Mapel)
        $mapelResponse = $this->actingAs($this->adminUser)->get(route('admin.konsultasi-mapel.index'));
        $mapelResponse->assertStatus(200);

        $storeSlotMapel = $this->actingAs($this->adminUser)->post(route('admin.konsultasi-mapel.store'), [
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'topic' => 'Tentoring Bab Peluang',
            'date_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'room_or_link' => 'Ruang 2',
            'max_quota' => 5,
        ]);
        $storeSlotMapel->assertRedirect(route('admin.konsultasi-mapel.index'));
        $this->assertDatabaseHas('subject_consultation_slots', ['topic' => 'Tentoring Bab Peluang']);

        // 2. BK Counseling (Kak Nisa)
        $bkUser = User::factory()->create(['name' => 'Kak Nisa']);
        $bkUser->syncRoles(['guru_bk']);

        $bkResponse = $this->actingAs($this->adminUser)->get(route('admin.konseling-bk.index'));
        $bkResponse->assertStatus(200);

        $storeSlotBk = $this->actingAs($this->adminUser)->post(route('admin.konseling-bk.store'), [
            'counselor_user_id' => $bkUser->id,
            'date_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'room_or_link' => 'Ruang BK',
            'max_quota' => 1,
        ]);
        $storeSlotBk->assertRedirect(route('admin.konseling-bk.index'));
        $this->assertDatabaseHas('counseling_slots', ['counselor_user_id' => $bkUser->id, 'room_or_link' => 'Ruang BK']);
    }
}
