<?php

use App\Http\Controllers\Admin\AcademicClassController;
use App\Http\Controllers\Admin\AdminTryoutQuestionController;
use App\Http\Controllers\Admin\AlterLmsController;
use App\Http\Controllers\Admin\AttendanceScannerController as AdminAttendanceScannerController;
use App\Http\Controllers\Admin\CounselingSlotController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnglishMasteryController;
use App\Http\Controllers\Admin\LessonScheduleController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectConsultationController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TryoutController as AdminTryoutController;
use App\Http\Controllers\Admin\WhatsAppGatewayController as AdminWhatsAppGatewayController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RedirectController;
use App\Http\Controllers\Counselor\AlertFlagController as CounselorAlertFlagController;
use App\Http\Controllers\Counselor\AttendanceReportController as CounselorAttendanceReportController;
use App\Http\Controllers\Counselor\BkConsultationController as CounselorBkConsultationController;
use App\Http\Controllers\Counselor\DashboardController as CounselorDashboardController;
use App\Http\Controllers\Counselor\TargetRecapController as CounselorTargetRecapController;
use App\Http\Controllers\Parent\BkConsultationController as ParentBkConsultationController;
use App\Http\Controllers\Parent\ChildProgressController as ParentChildProgressController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\Parent\MentorContactController as ParentMentorContactController;
use App\Http\Controllers\Report\AcademicProgressReportController;
use App\Http\Controllers\Report\TryoutReportController;
use App\Http\Controllers\Student\AlterLearningController as StudentAlterLearningController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\ConsultationController as StudentConsultationController;
use App\Http\Controllers\Student\CurriculumController as StudentCurriculumController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\SpeakingBookingController as StudentSpeakingBookingController;
use App\Http\Controllers\Student\SpeakingTaskController as StudentSpeakingTaskController;
use App\Http\Controllers\Student\StudentTryoutExamController;
use App\Http\Controllers\Student\TryoutController as StudentTryoutController;
use App\Http\Controllers\Student\VocabQuizController as StudentVocabQuizController;
use App\Http\Controllers\Teacher\AttendanceSessionController as TeacherAttendanceSessionController;
use App\Http\Controllers\Teacher\ConsultationSlotController as TeacherConsultationSlotController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\MapelController as TeacherMapelController;
use App\Http\Controllers\Teacher\OralRubricController as TeacherOralRubricController;
use App\Http\Controllers\Teacher\PembimbingController as TeacherPembimbingController;
use App\Http\Controllers\Teacher\ScannerController as TeacherScannerController;
use App\Http\Controllers\Teacher\SingleStudentProfileController as TeacherSingleStudentProfileController;
use App\Http\Controllers\Teacher\VocabMonitoringController as TeacherVocabMonitoringController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Common Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [RedirectController::class, 'handle'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ROLE: SISWA (Student)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:siswa', 'active_student'])
        ->prefix('siswa')
        ->name('siswa.')
        ->group(function () {
            Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

            // Presensi Siswa
            Route::get('/presensi/qr', [StudentAttendanceController::class, 'qrIdentity'])->name('presensi.qr');
            Route::get('/presensi/riwayat', [StudentAttendanceController::class, 'riwayat'])->name('riwayat.presensi');
            Route::get('/presensi/riwayat-list', [StudentAttendanceController::class, 'riwayat'])->name('presensi.riwayat');

            // Phase 4: Pembelajaran ALTER (AutoEnrollmentAccess Enforced)
            Route::middleware('auto_enrollment')->prefix('alter')->name('alter.')->group(function () {
                Route::get('/ruang-belajar/{chapterId?}', [StudentAlterLearningController::class, 'player'])->name('player');
                Route::get('/kurikulum', [StudentCurriculumController::class, 'index'])->name('kurikulum');
            });

            // Phase 4: English Mastery (Vocab CBT & Speaking Task)
            Route::prefix('english')->name('english.')->group(function () {
                Route::get('/vocab-quiz', [StudentVocabQuizController::class, 'index'])->name('vocab.index');
                Route::get('/vocab-essay', [StudentVocabQuizController::class, 'index'])->name('vocab.essay');
                Route::get('/vocab-quiz/{module}', [StudentVocabQuizController::class, 'take'])->name('vocab.take');
                Route::get('/vocab-essay/{module}', [StudentVocabQuizController::class, 'take'])->name('vocab.essay.take');
                Route::post('/vocab-quiz/{module}', [StudentVocabQuizController::class, 'submit'])->name('vocab.submit');
                Route::post('/vocab-essay/{module}', [StudentVocabQuizController::class, 'submit'])->name('vocab.essay.submit');

                Route::get('/digital-tasks', [StudentSpeakingTaskController::class, 'index'])->name('tasks.index');
                Route::get('/booking-setoran', [StudentSpeakingBookingController::class, 'create'])->name('speaking.booking');
                Route::post('/booking-setoran', [StudentSpeakingBookingController::class, 'store'])->name('speaking.store');
            });

            // Phase 3: Konsultasi & Tentoring
            Route::get('/konsultasi', [StudentConsultationController::class, 'index'])->name('konsultasi.index');
            Route::post('/konsultasi/{slot}/pesan', [StudentConsultationController::class, 'book'])->name('konsultasi.book');
            Route::delete('/konsultasi/{booking}/batal', [StudentConsultationController::class, 'cancel'])->name('konsultasi.cancel');

            // Phase 6: Evaluasi & Tryout SNBT
            Route::prefix('tryout')->name('tryout.')->group(function () {
                Route::get('/', [StudentTryoutController::class, 'index'])->name('index');
                Route::get('/{tryoutId}/take', [StudentTryoutExamController::class, 'take'])->name('take');
                Route::post('/{tryoutId}/take', [StudentTryoutExamController::class, 'submit'])->name('submit');
                Route::get('/{tryoutId}', [StudentTryoutController::class, 'show'])->name('show');
            });

            // Phase 6: Profil Akademik Terpadu
            Route::get('/profil', [StudentProfileController::class, 'index'])->name('profil');
            Route::post('/profil/complete-nisn', [StudentProfileController::class, 'updateNisn'])->name('profil.update-nisn');
        });

    Route::middleware(['auth', 'role:siswa'])->get('/tryout/{id}/take', fn ($id) => redirect()->route('siswa.tryout.take', $id));
    Route::middleware(['auth', 'role:siswa'])->get('/english/vocab-essay', fn () => redirect()->route('siswa.english.vocab.index'));
    Route::middleware(['auth', 'role:siswa'])->get('/english/vocab-essay/{module}', fn ($module) => redirect()->route('siswa.english.vocab.take', $module));

    /*
    |--------------------------------------------------------------------------
    | ROLE: GURU (Teacher & Mentor)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:guru'])
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

            // Dual-Role Teacher Views
            Route::get('/mapel', [TeacherMapelController::class, 'index'])->name('mapel.index');
            Route::get('/pembimbing', [TeacherPembimbingController::class, 'index'])->name('pembimbing.index');
            Route::post('/pembimbing/alerts/trigger', [TeacherPembimbingController::class, 'triggerMonthlyAlerts'])->name('pembimbing.alerts.trigger');
            Route::post('/pembimbing/alerts/{alertId}/resolve', [TeacherPembimbingController::class, 'resolveAlert'])->name('pembimbing.alerts.resolve');

            // Presensi Kelas
            Route::get('/presensi/scan', [TeacherScannerController::class, 'index'])->name('presensi.scanner');
            Route::post('/presensi/scan', [TeacherScannerController::class, 'checkIn'])->name('presensi.scan.checkin');
            Route::get('/presensi/log-sesi', [TeacherAttendanceSessionController::class, 'index'])->name('presensi.session.log');
            Route::put('/presensi/{attendance}', [TeacherAttendanceSessionController::class, 'update'])->name('presensi.update');

            // Bimbingan & Profil 360°
            Route::get('/bimbingan/siswa/{student}', [TeacherSingleStudentProfileController::class, 'show'])->name('bimbingan.student.profile');

            // Phase 4: Oral Evaluation Rubric & Vocab Monitoring
            Route::get('/bimbingan/oral-rubric/{bookingId}', [TeacherOralRubricController::class, 'edit'])->name('bimbingan.rubric.edit');
            Route::post('/bimbingan/oral-rubric/{bookingId}', [TeacherOralRubricController::class, 'store'])->name('bimbingan.rubric.store');
            Route::get('/bimbingan/monitoring-vocab', [TeacherVocabMonitoringController::class, 'index'])->name('bimbingan.vocab.monitor');

            // Konsultasi Mapel
            Route::get('/konsultasi', [TeacherConsultationSlotController::class, 'index'])->name('konsultasi.index');
            Route::post('/konsultasi', [TeacherConsultationSlotController::class, 'store'])->name('konsultasi.store');
            Route::delete('/konsultasi/{slot}', [TeacherConsultationSlotController::class, 'destroy'])->name('konsultasi.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | ROLE: GURU BK (Counselor)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:guru_bk'])
        ->prefix('bk')
        ->name('bk.')
        ->group(function () {
            Route::get('/dashboard', [CounselorDashboardController::class, 'index'])->name('dashboard');

            // System Flagging & Alerts
            Route::prefix('flagging')->name('flagging.')->group(function () {
                Route::get('/', [CounselorAlertFlagController::class, 'index'])->name('index');
                Route::post('/resolve/{flagId}', [CounselorAlertFlagController::class, 'resolve'])->name('resolve');
            });

            // Rekapitulasi Akademik
            Route::prefix('rekap')->name('rekap.')->group(function () {
                Route::get('/presensi', [CounselorAttendanceReportController::class, 'index'])->name('presensi');
                Route::get('/target-bulanan', [CounselorTargetRecapController::class, 'index'])->name('target');
            });

            // Konsultasi BK
            Route::prefix('konsultasi')->name('konsultasi.')->group(function () {
                Route::get('/sesi', [CounselorBkConsultationController::class, 'index'])->name('sessions');
                Route::post('/sesi/store', [CounselorBkConsultationController::class, 'store'])->name('store');
                Route::patch('/sesi/{id}/status', [CounselorBkConsultationController::class, 'updateStatus'])->name('updateStatus');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | ROLE: ORANG TUA (Parent)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:orang_tua'])
        ->prefix('ortu')
        ->name('ortu.')
        ->group(function () {
            Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');

            // Rekapitulasi Perkembangan Anak
            Route::prefix('rekap')->name('rekap.')->group(function () {
                Route::get('/presensi', [ParentChildProgressController::class, 'attendance'])->name('presensi');
                Route::get('/hasil-belajar', [ParentChildProgressController::class, 'academic'])->name('hasil');
            });

            // Konsultasi Wali Murid
            Route::prefix('konsultasi')->name('konsultasi.')->group(function () {
                Route::get('/booking-bk', [ParentBkConsultationController::class, 'create'])->name('booking');
                Route::post('/booking-bk', [ParentBkConsultationController::class, 'store'])->name('store');
            });

            // Kontak Pembimbing
            Route::get('/kontak-pembimbing', [ParentMentorContactController::class, 'index'])->name('kontak');
        });

    /*
    |--------------------------------------------------------------------------
    | ROLE: ADMIN (Central Management)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin|front_desk'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Master Data
            Route::resource('jurusan', MajorController::class);
            Route::resource('kelas', AcademicClassController::class);
            Route::resource('mata-pelajaran', SubjectController::class);
            Route::resource('guru', TeacherController::class);
            Route::resource('orang-tua', ParentController::class);
            Route::get('siswa/export-csv', [StudentController::class, 'exportCsv'])->name('siswa.export');
            Route::post('siswa/import-excel', [StudentController::class, 'importExcel'])->name('siswa.import');
            Route::resource('siswa', StudentController::class);

            // Operasional Akademik
            Route::resource('jadwal', LessonScheduleController::class);
            Route::get('presensi/scan', [AdminAttendanceScannerController::class, 'index'])->name('presensi.scanner');
            Route::post('presensi/scan', [AdminAttendanceScannerController::class, 'checkIn'])->name('presensi.scan.checkin');

            // Konten & Kurikulum (ALTER LMS, English Mastery, Tryout)
            Route::prefix('alter')->name('alter.')->group(function () {
                Route::get('/', [AlterLmsController::class, 'index'])->name('index');
                Route::get('/create', [AlterLmsController::class, 'createCourse'])->name('create');
                Route::post('/', [AlterLmsController::class, 'storeCourse'])->name('store');
                Route::get('/{course}', [AlterLmsController::class, 'showCourse'])->name('show');
                Route::get('/{course}/edit', [AlterLmsController::class, 'editCourse'])->name('edit');
                Route::put('/{course}', [AlterLmsController::class, 'updateCourse'])->name('update');
                Route::delete('/{course}', [AlterLmsController::class, 'destroyCourse'])->name('destroy');
                Route::post('/{course}/chapters', [AlterLmsController::class, 'storeChapter'])->name('chapter.store');
                Route::put('/chapters/{chapter}', [AlterLmsController::class, 'updateChapter'])->name('chapter.update');
                Route::delete('/chapters/{chapter}', [AlterLmsController::class, 'destroyChapter'])->name('chapter.destroy');
                Route::post('/chapters/{chapter}/problems', [AlterLmsController::class, 'storeProblem'])->name('problem.store');
                Route::delete('/problems/{problem}', [AlterLmsController::class, 'destroyProblem'])->name('problem.destroy');
            });

            Route::prefix('english')->name('english.')->group(function () {
                Route::get('/', [EnglishMasteryController::class, 'index'])->name('index');
                Route::post('/vocab', [EnglishMasteryController::class, 'storeVocabWord'])->name('vocab.store');
                Route::delete('/vocab/{word}', [EnglishMasteryController::class, 'destroyVocabWord'])->name('vocab.destroy');
                Route::post('/storytelling', [EnglishMasteryController::class, 'storeStorytelling'])->name('storytelling.store');
                Route::put('/storytelling/{script}', [EnglishMasteryController::class, 'updateStorytelling'])->name('storytelling.update');
                Route::delete('/storytelling/{script}', [EnglishMasteryController::class, 'destroyStorytelling'])->name('storytelling.destroy');
                Route::post('/wacana', [EnglishMasteryController::class, 'storeWacana'])->name('wacana.store');
                Route::put('/wacana/{script}', [EnglishMasteryController::class, 'updateWacana'])->name('wacana.update');
                Route::delete('/wacana/{script}', [EnglishMasteryController::class, 'destroyWacana'])->name('wacana.destroy');
            });

            // Tryout Question Management (Manual CRUD & Excel Bulk Import)
            Route::get('tryouts/{id}/questions', [AdminTryoutQuestionController::class, 'index'])->name('tryouts.questions.index');
            Route::post('tryouts/{id}/questions', [AdminTryoutQuestionController::class, 'store'])->name('tryouts.questions.store');
            Route::put('tryouts/{id}/questions/{question}', [AdminTryoutQuestionController::class, 'update'])->name('tryouts.questions.update');
            Route::delete('tryouts/{id}/questions/{question}', [AdminTryoutQuestionController::class, 'destroy'])->name('tryouts.questions.destroy');
            Route::post('tryouts/{id}/questions/import-excel', [AdminTryoutQuestionController::class, 'importExcel'])->name('tryouts.questions.import');

            Route::get('tryout/{id}/questions', [AdminTryoutQuestionController::class, 'index'])->name('tryout.questions.index');
            Route::post('tryout/{id}/questions', [AdminTryoutQuestionController::class, 'store'])->name('tryout.questions.store');
            Route::put('tryout/{id}/questions/{question}', [AdminTryoutQuestionController::class, 'update'])->name('tryout.questions.update');
            Route::delete('tryout/{id}/questions/{question}', [AdminTryoutQuestionController::class, 'destroy'])->name('tryout.questions.destroy');
            Route::post('tryout/{id}/questions/import-excel', [AdminTryoutQuestionController::class, 'importExcel'])->name('tryout.questions.import');

            Route::get('tryouts', fn () => redirect()->route('admin.tryout.index'));
            Route::post('tryout/{tryout}/subtests', [AdminTryoutController::class, 'storeSubtest'])->name('tryout.subtest.store');
            Route::delete('tryout/subtests/{subtest}', [AdminTryoutController::class, 'destroySubtest'])->name('tryout.subtest.destroy');
            Route::resource('tryout', AdminTryoutController::class);

            // Layanan Konsultasi Terpisah (Mapel vs BK)
            Route::prefix('konsultasi-mapel')->name('konsultasi-mapel.')->group(function () {
                Route::get('/', [SubjectConsultationController::class, 'index'])->name('index');
                Route::post('/', [SubjectConsultationController::class, 'store'])->name('store');
                Route::delete('/{slot}', [SubjectConsultationController::class, 'destroy'])->name('destroy');
                Route::post('/booking/{booking}/status', [SubjectConsultationController::class, 'updateBookingStatus'])->name('booking-status');
            });

            Route::prefix('konseling-bk')->name('konseling-bk.')->group(function () {
                Route::get('/', [CounselingSlotController::class, 'index'])->name('index');
                Route::post('/', [CounselingSlotController::class, 'store'])->name('store');
                Route::delete('/{slot}', [CounselingSlotController::class, 'destroy'])->name('destroy');
                Route::post('/booking/{booking}/status', [CounselingSlotController::class, 'updateBookingStatus'])->name('booking-status');
            });

            // WhatsApp Gateway
            Route::get('/whatsapp', [AdminWhatsAppGatewayController::class, 'index'])->name('whatsapp.index');
            Route::post('/whatsapp/test', [AdminWhatsAppGatewayController::class, 'testSend'])->name('whatsapp.test');
        });

    /*
    |--------------------------------------------------------------------------
    | SHARED / REPORTS EXPORT (PRINTABLE / PDF)
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/tryout/{tryoutScoreId}/pdf', [TryoutReportController::class, 'downloadPdf'])->name('tryout.pdf');
        Route::get('/student/{studentId}/progress-pdf', [AcademicProgressReportController::class, 'downloadPdf'])->name('progress.pdf');
    });
});
