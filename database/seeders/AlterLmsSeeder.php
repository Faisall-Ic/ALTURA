<?php

namespace Database\Seeders;

use App\Models\AlterChapter;
use App\Models\AlterCourse;
use App\Models\AlterProblem;
use App\Models\Major;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AlterLmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mipaMajor = Major::where('code', 'MIPA')->first();
        if (! $mipaMajor) {
            return;
        }

        $pmSubject = Subject::where('code', 'PM')->first();
        $fisSubject = Subject::where('code', 'FIS')->first();

        // Course 1: Penalaran Kuantitatif SNBT
        $course1 = AlterCourse::firstOrCreate(
            [
                'title' => 'Penalaran Matematika & Kuantitatif SNBT',
                'major_id' => $mipaMajor->id,
            ],
            [
                'subject_id' => $pmSubject?->id,
                'is_snbt_core' => true,
            ]
        );

        // Chapter 1
        $ch1 = AlterChapter::firstOrCreate(
            [
                'course_id' => $course1->id,
                'chapter_number' => 1,
            ],
            [
                'title' => 'Bab 1: Eksponen, Logaritma, dan Bentuk Akar',
                'youtube_video_id' => 'dQw4w9WgXcQ',
            ]
        );

        $problemsCh1 = [
            [
                'number' => 1,
                'type' => 'Uraian Pemahaman',
                'image' => 'assets/problems/eksponen_soal_1.png',
            ],
            [
                'number' => 2,
                'type' => 'Analisis HOTS',
                'image' => 'assets/problems/logaritma_soal_2.png',
            ],
            [
                'number' => 3,
                'type' => 'Aplikasi Soal Cerita',
                'image' => 'assets/problems/akar_soal_3.png',
            ],
        ];

        foreach ($problemsCh1 as $p) {
            AlterProblem::firstOrCreate(
                [
                    'chapter_id' => $ch1->id,
                    'problem_number' => $p['number'],
                ],
                [
                    'type_name' => $p['type'],
                    'problem_image_path' => $p['image'],
                ]
            );
        }

        // Chapter 2
        $ch2 = AlterChapter::firstOrCreate(
            [
                'course_id' => $course1->id,
                'chapter_number' => 2,
            ],
            [
                'title' => 'Bab 2: Persamaan & Pertidaksamaan Kuadrat',
                'youtube_video_id' => 'dQw4w9WgXcQ',
            ]
        );

        AlterProblem::firstOrCreate(
            [
                'chapter_id' => $ch2->id,
                'problem_number' => 1,
            ],
            [
                'type_name' => 'Uraian Analitis',
                'problem_image_path' => 'assets/problems/kuadrat_soal_1.png',
            ]
        );

        // Course 2: Fisika Mekanika
        $course2 = AlterCourse::firstOrCreate(
            [
                'title' => 'Fisika Mekanika & Kinematika Gerak',
                'major_id' => $mipaMajor->id,
            ],
            [
                'subject_id' => $fisSubject?->id,
                'is_snbt_core' => false,
            ]
        );

        $chFisika1 = AlterChapter::firstOrCreate(
            [
                'course_id' => $course2->id,
                'chapter_number' => 1,
            ],
            [
                'title' => 'Bab 1: Dinamika Partikel & Hukum Newton',
                'youtube_video_id' => 'dQw4w9WgXcQ',
            ]
        );

        AlterProblem::firstOrCreate(
            [
                'chapter_id' => $chFisika1->id,
                'problem_number' => 1,
            ],
            [
                'type_name' => 'Uraian Konseptual',
                'problem_image_path' => 'assets/problems/newton_soal_1.png',
            ]
        );
    }
}
