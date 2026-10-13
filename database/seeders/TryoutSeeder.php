<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Tryout;
use App\Models\TryoutScore;
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
                'date_start' => now()->subDays(14)->toDateString(),
                'date_end' => now()->subDays(10)->toDateString(),
                'is_active' => true,
            ]
        );

        $tryout2 = Tryout::firstOrCreate(
            ['title' => 'Tryout Akbar SNBT 2026 - Seri 2'],
            [
                'date_start' => now()->subDays(5)->toDateString(),
                'date_end' => now()->addDays(2)->toDateString(),
                'is_active' => true,
            ]
        );

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
