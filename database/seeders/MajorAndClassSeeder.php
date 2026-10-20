<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class MajorAndClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Master Majors
        $majorsData = [
            [
                'name' => 'Fokus SNBT Intensif',
                'code' => 'SNBT',
                'description' => 'Program intensif fokus subtes TPS, Literasi, dan Penalaran Matematika SNBT PTN.',
                'is_snbt_mandatory' => true,
                'classes' => [
                    ['name' => 'Intensif SNBT 1', 'code' => 'SNBT-01', 'grade_level' => 12, 'is_tka_active' => false],
                    ['name' => 'Intensif SNBT 2', 'code' => 'SNBT-02', 'grade_level' => 12, 'is_tka_active' => false],
                ],
            ],
            [
                'name' => 'MIPA (Matematika & IPA)',
                'code' => 'MIPA',
                'description' => 'Peminatan Matematika dan Ilmu Pengetahuan Alam dengan opsi kurikulum TKA Saintek.',
                'is_snbt_mandatory' => true,
                'classes' => [
                    ['name' => '12 MIPA 1', 'code' => '12-MIPA-01', 'grade_level' => 12, 'is_tka_active' => true],
                    ['name' => '12 MIPA 2 (Fokus SNBT)', 'code' => '12-MIPA-02', 'grade_level' => 12, 'is_tka_active' => false],
                ],
            ],
            [
                'name' => 'IPS (Ilmu Pengetahuan Sosial)',
                'code' => 'IPS',
                'description' => 'Peminatan Ilmu Pengetahuan Sosial dengan materi ekonomi, sosiologi, dan geografi.',
                'is_snbt_mandatory' => true,
                'classes' => [
                    ['name' => '12 IPS 1', 'code' => '12-IPS-01', 'grade_level' => 12, 'is_tka_active' => true],
                ],
            ],
            [
                'name' => 'SMK / Vokasi',
                'code' => 'SMK',
                'description' => 'Jalur persiapan lulusan kejuruan untuk seleksi nasional perguruan tinggi.',
                'is_snbt_mandatory' => false,
                'classes' => [
                    ['name' => '12 SMK Terpadu', 'code' => '12-SMK-01', 'grade_level' => 12, 'is_tka_active' => false],
                ],
            ],
        ];

        foreach ($majorsData as $majorItem) {
            $major = Major::firstOrCreate(
                ['code' => $majorItem['code']],
                [
                    'name' => $majorItem['name'],
                    'description' => $majorItem['description'],
                    'is_snbt_mandatory' => $majorItem['is_snbt_mandatory'],
                ]
            );

            foreach ($majorItem['classes'] as $classItem) {
                AcademicClass::firstOrCreate(
                    ['code' => $classItem['code']],
                    [
                        'major_id' => $major->id,
                        'name' => $classItem['name'],
                        'grade_level' => $classItem['grade_level'] ?? 12,
                        'is_tka_active' => $classItem['is_tka_active'] ?? true,
                    ]
                );
            }
        }

        // 2. Master Subjects (Separation of SNBT Core vs TKA)
        $subjectsData = [
            // SNBT Core (is_tka = false)
            ['name' => 'Penalaran Matematika', 'code' => 'PM', 'is_tka' => false, 'description' => 'Subtes SNBT Penalaran Matematika'],
            ['name' => 'Penalaran Umum (TPS)', 'code' => 'PU', 'is_tka' => false, 'description' => 'Subtes TPS Penalaran Umum'],
            ['name' => 'Pengetahuan Kuantitatif (TPS)', 'code' => 'PK', 'is_tka' => false, 'description' => 'Subtes TPS Pengetahuan Kuantitatif'],
            ['name' => 'Pemahaman Bacaan & Menulis (TPS)', 'code' => 'PBM', 'is_tka' => false, 'description' => 'Subtes TPS Pemahaman Bacaan & Menulis'],
            ['name' => 'Pengetahuan & Pemahaman Umum (TPS)', 'code' => 'PPU', 'is_tka' => false, 'description' => 'Subtes TPS Pengetahuan & Pemahaman Umum'],
            ['name' => 'Literasi Bahasa Indonesia', 'code' => 'LBI', 'is_tka' => false, 'description' => 'Subtes SNBT Literasi dalam Bahasa Indonesia'],
            ['name' => 'Literasi Bahasa Inggris', 'code' => 'LBE', 'is_tka' => false, 'description' => 'Subtes SNBT Literasi dalam Bahasa Inggris'],

            // TKA Saintek (is_tka = true)
            ['name' => 'Fisika', 'code' => 'FIS', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Fisika Saintek'],
            ['name' => 'Kimia', 'code' => 'KIM', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Kimia Saintek'],
            ['name' => 'Biologi', 'code' => 'BIO', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Biologi Saintek'],
            ['name' => 'Matematika Peminatan', 'code' => 'MAT-PEM', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Matematika Lanjut'],

            // TKA Soshum (is_tka = true)
            ['name' => 'Ekonomi', 'code' => 'EKO', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Ekonomi Soshum'],
            ['name' => 'Sosiologi', 'code' => 'SOS', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Sosiologi Soshum'],
            ['name' => 'Geografi', 'code' => 'GEO', 'is_tka' => true, 'description' => 'Mata Pelajaran TKA Geografi Soshum'],
        ];

        foreach ($subjectsData as $s) {
            Subject::firstOrCreate(
                ['code' => $s['code']],
                [
                    'name' => $s['name'],
                    'is_tka' => $s['is_tka'],
                    'description' => $s['description'],
                ]
            );
        }

        // 3. Map Major to Subjects (major_subject pivot)
        $mipa = Major::where('code', 'MIPA')->first();
        $ips = Major::where('code', 'IPS')->first();
        $snbt = Major::where('code', 'SNBT')->first();
        $smk = Major::where('code', 'SMK')->first();

        $snbtCoreIds = Subject::where('is_tka', false)->pluck('id')->toArray();
        $saintekIds = Subject::whereIn('code', ['FIS', 'KIM', 'BIO', 'MAT-PEM'])->pluck('id')->toArray();
        $soshumIds = Subject::whereIn('code', ['EKO', 'SOS', 'GEO'])->pluck('id')->toArray();

        if ($mipa) {
            $mipa->subjects()->syncWithoutDetaching(array_merge($snbtCoreIds, $saintekIds));
        }

        if ($ips) {
            $ips->subjects()->syncWithoutDetaching(array_merge($snbtCoreIds, $soshumIds));
        }

        if ($snbt) {
            $snbt->subjects()->syncWithoutDetaching($snbtCoreIds);
        }

        if ($smk) {
            $smk->subjects()->syncWithoutDetaching($snbtCoreIds);
        }
    }
}
