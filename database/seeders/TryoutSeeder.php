<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Tryout;
use App\Models\TryoutScore;
use App\Models\TryoutSubtest;
use Illuminate\Database\Seeder;

class TryoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tryout1 = Tryout::firstOrCreate(
            ['title' => 'Tryout Akbar SNBT 2026 - Seri 1'],
            [
                'batch_name' => 'Gelombang 1 - Pemetaan Awal',
                'date_start' => now()->subDays(14)->toDateString(),
                'date_end' => now()->subDays(10)->toDateString(),
                'is_active' => true,
            ]
        );

        $tryout2 = Tryout::firstOrCreate(
            ['title' => 'Tryout Akbar SNBT 2026 - Seri 2'],
            [
                'batch_name' => 'Gelombang 2 - Progresif',
                'date_start' => now()->subDays(5)->toDateString(),
                'date_end' => now()->addDays(2)->toDateString(),
                'is_active' => true,
            ]
        );

        // Seed Subtests for each tryout
        $subtestsDef = [
            ['name' => 'Tes Potensi Skolastik (TPS)', 'question_count' => 30, 'duration_minutes' => 45],
            ['name' => 'Literasi Bahasa Indonesia', 'question_count' => 25, 'duration_minutes' => 35],
            ['name' => 'Literasi Bahasa Inggris', 'question_count' => 20, 'duration_minutes' => 30],
            ['name' => 'Penalaran Matematika', 'question_count' => 20, 'duration_minutes' => 30],
        ];

        foreach ([$tryout1, $tryout2] as $to) {
            foreach ($subtestsDef as $st) {
                TryoutSubtest::firstOrCreate(
                    [
                        'tryout_id' => $to->id,
                        'name' => $st['name'],
                    ],
                    [
                        'question_count' => $st['question_count'],
                        'duration_minutes' => $st['duration_minutes'],
                    ]
                );
            }
        }

        $students = Student::all();

        foreach ($students as $student) {
            // Tryout 1 Score
            TryoutScore::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'tryout_id' => $tryout1->id,
                ],
                [
                    'subtest_tps' => 645.50,
                    'subtest_indo' => 670.00,
                    'subtest_inggris' => 630.25,
                    'subtest_matematika' => 610.75,
                    'average_score' => 639.13,
                ]
            );

            // Tryout 2 Score (Slight improvement)
            TryoutScore::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'tryout_id' => $tryout2->id,
                ],
                [
                    'subtest_tps' => 680.00,
                    'subtest_indo' => 715.50,
                    'subtest_inggris' => 665.00,
                    'subtest_matematika' => 650.00,
                    'average_score' => 677.63,
                ]
            );
        }
    }
}
