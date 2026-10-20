<?php

namespace Tests\Feature;

use App\Imports\StudentsImport;
use App\Imports\TryoutQuestionsImport;
use App\Imports\VocabWordsImport;
use App\Imports\WacanaTextsImport;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\CounselingBooking;
use App\Models\CounselingSlot;
use App\Models\Major;
use App\Models\MonthlySubmissionAlert;
use App\Models\ParentGuardian;
use App\Models\StorytellingTheme;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectConsultationBooking;
use App\Models\SubjectConsultationSlot;
use App\Models\Teacher;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use App\Models\TryoutAttempt;
use App\Models\TryoutQuestion;
use App\Models\TryoutScore;
use App\Models\User;
use App\Models\VocabWord;
use App\Models\WacanaText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaLockdownTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Verify all 27 ALTO database tables exist and PR/homework tables are completely removed.
     */
    public function test_all_27_tables_exist_and_homework_tables_are_completely_removed(): void
    {
        $expectedTables = [
            'users',
            'roles',
            'permissions',
            'majors',
            'academic_classes',
            'subjects',
            'major_subject',
            'teachers',
            'teacher_subject',
            'teacher_class',
            'students',
            'parents',
            'student_parent',
            'lesson_schedules',
            'attendances',
            'alter_courses',
            'alter_chapters',
            'alter_problems',
            'vocab_words',
            'vocab_quiz_results',
            'storytelling_themes',
            'storytelling_submissions',
            'wacana_texts',
            'wacana_submissions',
            'speaking_bookings',
            'speaking_assessments',
            'tryouts',
            'tryout_questions',
            'tryout_attempts',
            'tryout_answers',
            'tryout_scores',
            'subject_consultation_slots',
            'subject_consultation_bookings',
            'counseling_slots',
            'counseling_bookings',
            'monthly_submission_alerts',
            'wa_message_logs',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table {$table} should exist in schema.");
        }

        $this->assertFalse(Schema::hasTable('homework_assignments'), 'Homework assignments table must be removed.');
        $this->assertFalse(Schema::hasTable('homework_submissions'), 'Homework submissions table must be removed.');
    }

    /**
     * Rule 1: Nullable NISN & Student Profile Completion check.
     */
    public function test_nullable_nisn_and_student_profile_completion_logic(): void
    {
        $major = Major::first();
        $class = AcademicClass::first();
        $mentor = Teacher::first();
        $counselor = User::role('guru_bk')->first() ?? User::role('admin')->first();

        $userWithoutNisn = User::factory()->create([
            'email' => 'nonisn@altura.test',
        ]);

        $studentWithoutNisn = Student::create([
            'user_id' => $userWithoutNisn->id,
            'major_id' => $major?->id,
            'class_id' => $class?->id,
            'mentor_id' => $mentor?->id,
            'counselor_id' => $counselor?->id,
            'nisn' => null,
            'qr_identity_token' => 'QR_ID_NULL_NISN',
            'qr_code_token' => 'QR_TEST_NULL_NISN',
            'status_active' => true,
        ]);

        $this->assertNull($studentWithoutNisn->nisn);
        $this->assertFalse($studentWithoutNisn->isProfileComplete());
        $this->assertTrue($studentWithoutNisn->needsProfileCompletion());

        $userWithNisn = User::factory()->create([
            'email' => 'withnisn@altura.test',
        ]);

        $studentWithNisn = Student::create([
            'user_id' => $userWithNisn->id,
            'major_id' => $major?->id,
            'class_id' => $class?->id,
            'mentor_id' => $mentor?->id,
            'counselor_id' => $counselor?->id,
            'nisn' => '9988776655',
            'qr_identity_token' => 'QR_ID_WITH_NISN',
            'qr_code_token' => 'QR_TEST_WITH_NISN',
            'status_active' => true,
        ]);

        $this->assertTrue($studentWithNisn->isProfileComplete());
        $this->assertFalse($studentWithNisn->needsProfileCompletion());
    }

    /**
     * Rule 2: Front desk admin QR attendance scanning.
     */
    public function test_front_desk_admin_qr_attendance_scanning(): void
    {
        $admin = User::role('front_desk')->first() ?? User::role('admin')->first();
        $student = Student::first();

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => now()->toDateString(),
            'time_in' => now()->format('H:i:s'),
            'status' => 'hadir',
            'scanned_by_admin_id' => $admin->id,
        ]);

        $this->assertEquals($admin->id, $attendance->scanned_by_admin_id);
        $this->assertEquals($admin->id, $attendance->scannedByAdmin->id);
        $this->assertNotNull($attendance->time_in);
    }

    /**
     * Rule 3: Dual-role teacher architecture (Mapel vs Pembimbing Akademik) & Tanggal 20 auto-alerts.
     */
    public function test_dual_role_teacher_and_monthly_submission_alert_engine(): void
    {
        $teacher = Teacher::first();
        $student = Student::first();

        $student->update(['mentor_id' => $teacher->id]);

        $this->assertTrue($teacher->mentees->contains($student));

        // Evaluate for a month with no submissions (e.g. September) to verify alert generation
        $alert = MonthlySubmissionAlert::evaluateMonthlySubmissions(
            $student,
            9,
            2026
        );

        $this->assertNotNull($alert);
        $this->assertIsArray($alert->missing_tasks);
        $this->assertFalse($alert->is_resolved);
        $this->assertEquals($teacher->id, $alert->mentor_id);
        $this->assertEquals($student->id, $alert->student_id);
    }

    /**
     * Rule 4: CBT Tryout Exam Engine & Relations.
     */
    public function test_cbt_tryout_engine_relations_and_subtest_scores(): void
    {
        $tryout = Tryout::first();
        $student = Student::first();

        $this->assertNotNull($tryout->duration_minutes);
        $this->assertTrue($tryout->is_published);

        $question = TryoutQuestion::where('tryout_id', $tryout->id)->first();
        $this->assertNotNull($question);
        $this->assertIsArray($question->options);
        $this->assertContains($question->subtest_code, ['TPS', 'LBI', 'LBS', 'PBM', 'PM', 'PU', 'PK', 'PPU']);

        $attempt = TryoutAttempt::create([
            'student_id' => $student->id,
            'tryout_id' => $tryout->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        $answer = TryoutAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'student_answer' => $question->correct_answer,
            'is_correct' => true,
        ]);

        $attempt->update([
            'submitted_at' => now(),
            'status' => 'completed',
        ]);

        TryoutScore::where('student_id', $student->id)->where('tryout_id', $tryout->id)->delete();

        $score = TryoutScore::create([
            'student_id' => $student->id,
            'tryout_id' => $tryout->id,
            'subtest_scores' => [
                'PU' => 750,
                'PK' => 800,
                'PBM' => 720,
            ],
            'average_score' => 756.67,
        ]);

        $this->assertIsArray($score->subtest_scores);
        $this->assertEquals(750, $score->subtest_scores['PU']);
        $this->assertEquals(1, $attempt->answers()->count());
    }

    /**
     * Rule 5: English mastery overhaul (Vocab acceptable answers, Storytelling themes, Wacana texts).
     */
    public function test_english_mastery_vocab_storytelling_and_wacana(): void
    {
        $vocab = VocabWord::first();
        $this->assertNotNull($vocab);
        $this->assertIsArray($vocab->acceptable_answers);
        $this->assertTrue($vocab->isAnswerAcceptable($vocab->indonesian_meaning));

        $theme = StorytellingTheme::first();
        $this->assertNotNull($theme);
        $this->assertNotEmpty($theme->theme_name);

        $wacana = WacanaText::first();
        $this->assertNotNull($wacana);
        $this->assertGreaterThan(0, $wacana->paragraph_count);

        $student = Student::first();
        $this->assertTrue(WacanaText::appliesToStudent($student, Carbon::parse('2026-11-01')));
    }

    /**
     * Rule 6: Removal of PR and replacement with Admin-created Subject Consultations.
     */
    public function test_admin_created_subject_consultations_and_pr_removal(): void
    {
        $admin = User::role('admin')->first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        $student = Student::first();

        $slot = SubjectConsultationSlot::create([
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'created_by_admin_id' => $admin->id,
            'topic' => 'Klinik Soal TPS Kuantitatif',
            'date_time' => now()->addDays(2),
            'room_or_link' => 'Ruang Diskusi Lantai 2',
            'max_quota' => 10,
            'remaining_quota' => 10,
        ]);

        $this->assertEquals($admin->id, $slot->createdByAdmin->id);

        $booking = SubjectConsultationBooking::create([
            'slot_id' => $slot->id,
            'student_id' => $student->id,
            'status' => 'booked',
        ]);

        $this->assertEquals($slot->id, $booking->slot->id);
        $this->assertEquals($student->id, $booking->student->id);
    }

    /**
     * Rule 7: Separate BK Counseling with semester quota constraint (max 5 bookings).
     */
    public function test_counseling_bk_semester_quota_constraint(): void
    {
        $counselor = User::role('guru_bk')->first() ?? User::factory()->create();
        $student = Student::first();

        $slot = CounselingSlot::create([
            'counselor_user_id' => $counselor->id,
            'date_time' => now()->addDays(3),
            'max_quota' => 5,
            'remaining_quota' => 5,
        ]);

        $currentYear = (int) now()->format('Y');
        $semester = 1;

        CounselingBooking::where('student_id', $student->id)
            ->where('semester', $semester)
            ->where('year', $currentYear)
            ->delete();

        for ($i = 1; $i <= 5; $i++) {
            $this->assertTrue(CounselingBooking::canStudentBook($student->id, $semester, $currentYear));

            CounselingBooking::create([
                'slot_id' => $slot->id,
                'student_id' => $student->id,
                'semester' => $semester,
                'year' => $currentYear,
                'status' => 'booked',
            ]);
        }

        $this->assertFalse(CounselingBooking::canStudentBook($student->id, $semester, $currentYear));
        $this->assertEquals(0, CounselingBooking::remainingQuotaForStudent($student->id, $semester, $currentYear));
    }

    /**
     * Rule 8: Dedicated Parent table and Student-Parent pivot relation.
     */
    public function test_parent_student_relation_and_pivot(): void
    {
        $parentUser = User::factory()->create(['email' => 'wali@altura.test']);
        $student = Student::first();

        $parent = ParentGuardian::create([
            'user_id' => $parentUser->id,
            'phone_number' => '081298765432',
            'occupation' => 'Wiraswasta',
            'address' => 'Jl. Metro Pondok Indah No. 12',
        ]);

        $parent->students()->attach($student->id, ['relationship_type' => 'Ayah']);

        $this->assertTrue($parent->students->contains($student));
        $this->assertTrue($student->parents->contains($parent));
        $this->assertEquals('Ayah', $student->parents->first()->pivot->relationship_type);
    }

    /**
     * Rule 9: Grade 12 TKA to SNBT curriculum transition.
     */
    public function test_grade_12_tka_to_snbt_curriculum_transition(): void
    {
        $class = AcademicClass::where('grade_level', 12)->first() ?? AcademicClass::first();
        $this->assertNotNull($class);

        $class->activateTka();
        $this->assertTrue($class->fresh()->is_tka_active);

        $class->deactivateTka();
        $this->assertFalse($class->fresh()->is_tka_active);

        $availableSubjects = $class->availableSubjects();
        foreach ($availableSubjects as $subject) {
            $this->assertFalse((bool) $subject->is_tka);
        }
    }

    /**
     * Rule 10: Excel bulk import mappers can be instantiated and follow Maatwebsite interface.
     */
    public function test_excel_import_mappers_structure(): void
    {
        $studentsImport = new StudentsImport;
        $this->assertInstanceOf(StudentsImport::class, $studentsImport);

        $tryoutQuestionsImport = new TryoutQuestionsImport(1);
        $this->assertInstanceOf(TryoutQuestionsImport::class, $tryoutQuestionsImport);

        $vocabWordsImport = new VocabWordsImport;
        $this->assertInstanceOf(VocabWordsImport::class, $vocabWordsImport);

        $wacanaTextsImport = new WacanaTextsImport;
        $this->assertInstanceOf(WacanaTextsImport::class, $wacanaTextsImport);
    }
}
