<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\HomeworkAssignment;
use App\Models\HomeworkSubmission;
use App\Models\LessonSchedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicOperationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $class = AcademicClass::where('code', '12-MIPA-01')->first();
        $pmSubject = Subject::where('code', 'PM')->first();
        $lbeSubject = Subject::where('code', 'LBE')->first();
        $fisSubject = Subject::where('code', 'FIS')->first();

        $teacher1 = Teacher::first(); // Pak Budi
        $teacher2 = Teacher::skip(1)->first(); // Ibu Siti

        if (! $class || ! $teacher1 || ! $pmSubject) {
            return;
        }

        // 1. Lesson Schedules
        $schedule1 = LessonSchedule::firstOrCreate(
            [
                'class_id' => $class->id,
                'subject_id' => $pmSubject->id,
                'day_of_week' => 'Senin',
                'time_start' => '08:00:00',
            ],
            [
                'teacher_id' => $teacher1->id,
                'room' => 'Ruang Teori 12-A',
                'time_end' => '09:40:00',
                'note' => 'Pendalaman materi Eksponen & Logaritma HOTS',
            ]
        );

        if ($lbeSubject && $teacher2) {
            LessonSchedule::firstOrCreate(
                [
                    'class_id' => $class->id,
                    'subject_id' => $lbeSubject->id,
                    'day_of_week' => 'Rabu',
                    'time_start' => '10:00:00',
                ],
                [
                    'teacher_id' => $teacher2->id,
                    'room' => 'Lab Bahasa Alto',
                    'time_end' => '11:40:00',
                    'note' => 'Literasi Reading Comprehension & Vocab Diagnostic',
                ]
            );
        }

        if ($fisSubject && $teacher1) {
            LessonSchedule::firstOrCreate(
                [
                    'class_id' => $class->id,
                    'subject_id' => $fisSubject->id,
                    'day_of_week' => 'Kamis',
                    'time_start' => '13:00:00',
                ],
                [
                    'teacher_id' => $teacher1->id,
                    'room' => 'Ruang Teori 12-A',
                    'time_end' => '14:40:00',
                    'note' => 'Fisika Dinamika Partikel & Gerak Lurus',
                ]
            );
        }

        // 2. Homework Assignments
        $hw1 = HomeworkAssignment::firstOrCreate(
            [
                'subject_id' => $pmSubject->id,
                'class_id' => $class->id,
                'title' => 'Latihan Mandiri: 15 Soal Penalaran Matematika Persamaan Kuadrat',
            ],
            [
                'teacher_id' => $teacher1->id,
                'instructions' => 'Kerjakan pada buku catatan latihan, lalu foto lembar jawaban dan unggah file PDF/JPG Anda sebelum batas tenggat.',
                'due_date' => Carbon::now()->addDays(5)->setHour(23)->setMinute(59)->setSecond(0),
            ]
        );

        // 3. Homework Submission for student
        $student = Student::first();
        if ($student) {
            HomeworkSubmission::firstOrCreate(
                [
                    'assignment_id' => $hw1->id,
                    'student_id' => $student->id,
                ],
                [
                    'submission_file_path' => 'submissions/tugas_pm_ahmad_rizky.pdf',
                    'student_note' => 'Sudah selesai pak, nomor 14 menggunakan cara cepat diskriminan.',
                    'grade' => 92.00,
                    'feedback' => 'Sangat rapi dan metode pembuktian langkah nomor 14 tepat sekali.',
                    'submitted_at' => Carbon::now()->subDays(1),
                ]
            );
        }
    }
}
