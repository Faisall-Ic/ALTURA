<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorAndClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majorsData = [
            [
                'name' => 'Fokus SNBT Intensif',
                'code' => 'SNBT',
                'is_snbt_mandatory' => true,
                'classes' => [
                    ['name' => 'Intensif SNBT 1', 'code' => 'SNBT-01'],
                    ['name' => 'Intensif SNBT 2', 'code' => 'SNBT-02'],
                ],
            ],
            [
                'name' => 'MIPA (Matematika & IPA)',
                'code' => 'MIPA',
                'is_snbt_mandatory' => true,
                'classes' => [
                    ['name' => '12 MIPA 1', 'code' => '12-MIPA-01'],
                    ['name' => '12 MIPA 2', 'code' => '12-MIPA-02'],
                ],
            ],
            [
                'name' => 'IPS (Ilmu Pengetahuan Sosial)',
                'code' => 'IPS',
                'is_snbt_mandatory' => true,
                'classes' => [
                    ['name' => '12 IPS 1', 'code' => '12-IPS-01'],
                ],
            ],
            [
                'name' => 'SMK / Vokasi',
                'code' => 'SMK',
                'is_snbt_mandatory' => false,
                'classes' => [
                    ['name' => '12 SMK Terpadu', 'code' => '12-SMK-01'],
                ],
            ],
        ];

        foreach ($majorsData as $majorItem) {
            $major = Major::firstOrCreate(
                ['code' => $majorItem['code']],
                [
                    'name' => $majorItem['name'],
                    'is_snbt_mandatory' => $majorItem['is_snbt_mandatory'],
                ]
            );

            foreach ($majorItem['classes'] as $classItem) {
                AcademicClass::firstOrCreate(
                    ['code' => $classItem['code']],
                    [
                        'major_id' => $major->id,
                        'name' => $classItem['name'],
                    ]
                );
            }
        }
    }
}
