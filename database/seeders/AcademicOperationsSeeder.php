<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\LessonSchedule;
use App\Models\MonthlySubmissionAlert;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
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

        // 1. Lesson Schedules (Rule 10: Dynamic Syllabus & Lesson Schedules)
        LessonSchedule::firstOrCreate(
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
                    'note' => 'Fisika Dinamika Partikel & Gerak Lurus (TKA)',
                ]
            );
        }

        // 2. Tanggal 20 Auto-Alert System (Rule 3: monthly_submission_alerts)
        $students = Student::all();
        foreach ($students as $std) {
            if ($std->mentor_id) {
                // If student has incomplete tasks (e.g. Student 2 has missing submissions)
                if ($std->needsProfileCompletion() || $std->id > 1) {
                    MonthlySubmissionAlert::firstOrCreate(
                        [
                            'student_id' => $std->id,
                            'month' => 10,
                            'year' => 2026,
                        ],
                        [
                            'mentor_id' => $std->mentor_id,
                            'missing_tasks' => ['Vocab Quiz Modul 2', 'Storytelling Theme Oktober', 'ALTER Chapter 3'],
                            'is_resolved' => false,
                        ]
                    );
                }
            }
        }
    }
}
