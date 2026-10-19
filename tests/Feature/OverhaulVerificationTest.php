<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tryout;
use App\Models\TryoutQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OverhaulVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test Front Desk Entrance QR scanner interface and check-in endpoint.
     */
    public function test_front_desk_scanner_and_checkin_endpoint(): void
    {
        $frontDeskUser = User::where('email', 'frontdesk@altura.test')->firstOrFail();
        $student = Student::with('user')->firstOrFail();

        // 1. Can view front desk scanner page
        $response = $this->actingAs($frontDeskUser)->get(route('admin.presensi.scanner'));
        $response->assertStatus(200);
        $response->assertSee('Scanner Presensi Masuk Siswa');
        $response->assertSee('Front Desk Ka-Administrasi');

        // Clear today's attendance for this student to test clean checkin
        Attendance::where('student_id', $student->id)->whereDate('date', now()->toDateString())->delete();

        // 2. Can perform QR checkin
        $checkinResponse = $this->actingAs($frontDeskUser)->postJson(route('admin.presensi.scan.checkin'), [
            'token' => $student->qr_identity_token,
            'status' => 'hadir',
        ]);

        $checkinResponse->assertStatus(200);
        $checkinResponse->assertJson([
            'success' => true,
            'attendance' => [
                'student_name' => $student->user->name,
                'status' => 'hadir',
            ],
        ]);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'scanned_by_admin_id' => $frontDeskUser->id,
            'status' => 'hadir',
        ]);
    }

    /**
     * Test Student CBT Live Exam take & submit engine.
     */
    public function test_student_cbt_live_exam_take_and_submit(): void
    {
        $studentUser = User::where('email', 'siswa@altura.test')->firstOrFail();
        $student = $studentUser->student;
        $tryout = Tryout::firstOrFail();

        // Ensure questions exist
        if ($tryout->questions->isEmpty()) {
            TryoutQuestion::create([
                'tryout_id' => $tryout->id,
                'subtest_code' => 'PU',
                'question_text' => 'Berapa hasil dari 5 + 7?',
                'options' => ['A' => '10', 'B' => '12', 'C' => '14', 'D' => '16', 'E' => '18'],
                'correct_answer' => 'B',
            ]);
        }

        // 1. Can view CBT live exam interface
        $takeResponse = $this->actingAs($studentUser)->get(route('siswa.tryout.take', $tryout->id));
        $takeResponse->assertStatus(200);
        $takeResponse->assertSee($tryout->title);
        $takeResponse->assertSee('Sisa Waktu');
        $takeResponse->assertSee('Nomor Soal');

        // 2. Can submit CBT exam answers
        $firstQuestion = $tryout->questions()->first();
        $submitResponse = $this->actingAs($studentUser)->postJson(route('siswa.tryout.submit', $tryout->id), [
            'answers' => [
                $firstQuestion->id => 'B',
            ],
        ]);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJson([
            'success' => true,
            'redirect' => route('siswa.tryout.show', $tryout->id),
        ]);

        $this->assertDatabaseHas('tryout_attempts', [
            'student_id' => $student->id,
            'tryout_id' => $tryout->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('tryout_scores', [
            'student_id' => $student->id,
            'tryout_id' => $tryout->id,
        ]);
    }

    /**
     * Test Dual-Role Teacher Mapel and Pembimbing views.
     */
    public function test_dual_role_teacher_views(): void
    {
        $teacherUser = User::where('email', 'budi.guru@altura.test')->firstOrFail();

        // 1. Subject Teacher View (Mapel)
        $mapelResponse = $this->actingAs($teacherUser)->get(route('guru.mapel.index'));
        $mapelResponse->assertStatus(200);
        $mapelResponse->assertSee('Peran Akademik: Guru Mata Pelajaran');
        $mapelResponse->assertSee('Monitoring Kehadiran Kelas');

        // 2. Pembimbing Akademik View (360)
        $pembimbingResponse = $this->actingAs($teacherUser)->get(route('guru.pembimbing.index'));
        $pembimbingResponse->assertStatus(200);
        $pembimbingResponse->assertSee('Peran Akademik: Pembimbing Akademik (Mentor)');
        $pembimbingResponse->assertSee('Daftar Siswa Bimbingan (Mentees)');
        $pembimbingResponse->assertSee('Alert Tanggal 20');

        // 3. Tanggal 20 Auto-Alert evaluation trigger
        $triggerResponse = $this->actingAs($teacherUser)->post(route('guru.pembimbing.alerts.trigger'), [
            'month' => 9,
            'year' => 2026,
        ]);
        $triggerResponse->assertRedirect(route('guru.pembimbing.index'));
        $triggerResponse->assertSessionHas('success');
    }

    /**
     * Test Student Profile completion (NISN update).
     */
    public function test_student_profile_completion_nisn_update(): void
    {
        $studentUser = User::where('email', 'dewi.siswa@altura.test')->firstOrFail();
        $student = $studentUser->student;

        $this->assertNull($student->nisn);
        $this->assertTrue($student->needsProfileCompletion());

        $updateResponse = $this->actingAs($studentUser)->post(route('siswa.profil.update-nisn'), [
            'nisn' => '0091827364',
        ]);

        $updateResponse->assertSessionHas('success');
        $student->refresh();
        $this->assertEquals('0091827364', $student->nisn);
        $this->assertFalse($student->needsProfileCompletion());
    }

    /**
     * Test all role dashboards render successfully with HTTP 200.
     */
    public function test_all_role_dashboards_render_successfully(): void
    {
        // 1. Admin
        $admin = User::where('email', 'admin@altura.test')->firstOrFail();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200);

        // 2. Guru (both Mapel and Mentor view modes)
        $teacher = User::where('email', 'budi.guru@altura.test')->firstOrFail();
        $this->actingAs($teacher)->get(route('guru.dashboard', ['view' => 'guru_mapel']))->assertStatus(200);
        $this->actingAs($teacher)->get(route('guru.dashboard', ['view' => 'mentor']))->assertStatus(200);

        // 3. Siswa
        $siswa = User::where('email', 'siswa@altura.test')->firstOrFail();
        $this->actingAs($siswa)->get(route('siswa.dashboard'))->assertStatus(200);

        // 4. Guru BK
        $bk = User::where('email', 'nisa.bk@altura.test')->firstOrFail();
        $this->actingAs($bk)->get(route('bk.dashboard'))->assertStatus(200);

        // 5. Ortu
        $ortu = User::where('email', 'ortu@altura.test')->firstOrFail();
        $this->actingAs($ortu)->get(route('ortu.dashboard'))->assertStatus(200);
    }
}
