<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use App\Models\TryoutAttempt;
use App\Models\TryoutQuestion;
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
        // 1. Create Tryout Packages (Rule 4: Full CBT Tryout Engine)
        $tryout1 = Tryout::firstOrCreate(
            ['title' => 'Simulasi UTBK-SNBT Akbar ALTO 2026 - Seri 1'],
            [
                'duration_minutes' => 195,
                'batch_name' => 'Gelombang 1 - Pemetaan Awal',
                'date_start' => now()->subDays(14)->toDateString(),
                'date_end' => now()->subDays(10)->toDateString(),
                'is_published' => true,
                'is_active' => true,
            ]
        );

        $tryout2 = Tryout::firstOrCreate(
            ['title' => 'Simulasi UTBK-SNBT Akbar ALTO 2026 - Seri 2'],
            [
                'duration_minutes' => 195,
                'batch_name' => 'Gelombang 2 - Progresif & Prediksi',
                'date_start' => now()->subDays(5)->toDateString(),
                'date_end' => now()->addDays(7)->toDateString(),
                'is_published' => true,
                'is_active' => true,
            ]
        );

        // 2. Subtests
        $subtestsDef = [
            ['name' => 'Kemampuan Penalaran Umum (PU)', 'code' => 'PU', 'question_count' => 30, 'duration_minutes' => 30],
            ['name' => 'Pengetahuan & Pemahaman Umum (PPU)', 'code' => 'PPU', 'question_count' => 20, 'duration_minutes' => 15],
            ['name' => 'Pemahaman Bacaan & Menulis (PBM)', 'code' => 'PBM', 'question_count' => 20, 'duration_minutes' => 25],
            ['name' => 'Pengetahuan Kuantitatif (PK)', 'code' => 'PK', 'question_count' => 15, 'duration_minutes' => 20],
            ['name' => 'Literasi Bahasa Indonesia (LBI)', 'code' => 'LBI', 'question_count' => 30, 'duration_minutes' => 45],
            ['name' => 'Literasi Bahasa Inggris (LBE/LBS)', 'code' => 'LBS', 'question_count' => 20, 'duration_minutes' => 30],
            ['name' => 'Penalaran Matematika (PM)', 'code' => 'PM', 'question_count' => 20, 'duration_minutes' => 30],
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

        // 3. Questions across subtests (Rule 4: TPS, LBI, LBS, PBM, PM, PU, PK, PPU)
        $sampleQuestions = [
            [
                'subtest_code' => 'PU',
                'question_text' => 'Semua siswa ALTO yang rajin belajar lulus ujian PTN impian. Sebagian siswa kelas 12 MIPA adalah siswa ALTO yang rajin belajar. Kesimpulan yang paling tepat adalah...',
                'options' => [
                    'A' => 'Semua siswa kelas 12 MIPA lulus ujian PTN impian.',
                    'B' => 'Sebagian siswa kelas 12 MIPA lulus ujian PTN impian.',
                    'C' => 'Tidak ada siswa kelas 12 MIPA yang lulus ujian PTN impian.',
                    'D' => 'Semua siswa yang lulus PTN impian berasal dari kelas 12 MIPA.',
                    'E' => 'Sebagian siswa ALTO tidak mengikuti ujian PTN impian.',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Silogisme kategorik parsial: premis partikular menghasilkan kesimpulan partikular.',
            ],
            [
                'subtest_code' => 'PK',
                'question_text' => 'Jika 3x + 2y = 12 dan 2x + 3y = 13, maka nilai dari x^2 - y^2 adalah...',
                'options' => [
                    'A' => '-5',
                    'B' => '0',
                    'C' => '5',
                    'D' => '7',
                    'E' => '25',
                ],
                'correct_answer' => 'A',
                'explanation' => 'Jumlahkan kedua persamaan: 5(x+y)=25 -> x+y=5. Kurangkan kedua persamaan: x-y=-1. Maka x^2 - y^2 = (x+y)(x-y) = 5 * (-1) = -5.',
            ],
            [
                'subtest_code' => 'PM',
                'question_text' => 'Sebuah tangki air berbentuk silinder memiliki diameter 14 meter dan tinggi 10 meter. Jika tangki terisi 75%, volume air yang ada dalam tangki adalah... (pi = 22/7)',
                'options' => [
                    'A' => '1.155 m^3',
                    'B' => '1.540 m^3',
                    'C' => '1.200 m^3',
                    'D' => '1.050 m^3',
                    'E' => '770 m^3',
                ],
                'correct_answer' => 'A',
                'explanation' => 'V_total = pi * r^2 * t = (22/7) * 7 * 7 * 10 = 1.540 m^3. V_air = 75% * 1.540 = 1.155 m^3.',
            ],
            [
                'subtest_code' => 'PBM',
                'question_text' => 'Manakah di antara kalimat berikut yang memuat penggunaan tanda baca koma (,) yang paling tepat sesuai EYD V?',
                'options' => [
                    'A' => 'Saya ingin membeli buku itu tetapi, uang saya belum mencukupi.',
                    'B' => 'Meskipun hari hujan lebat, para siswa tetap menghadiri kelas bimbingan ALTO.',
                    'C' => 'Ibu membeli apel, jeruk dan mangga, di pasar tradisional.',
                    'D' => 'Oleh karena itu kita, harus giat belajar setiap hari.',
                    'E' => 'Dia mengatakan, bahwa ujian tahun ini akan diselenggarakan lebih awal.',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Anak kalimat yang mendahului induk kalimat wajib dipisahkan dengan tanda koma.',
            ],
            [
                'subtest_code' => 'PPU',
                'question_text' => 'Kata "akselerasi" dalam konteks "akselerasi pembangunan ekonomi hijau" memiliki padanan makna terdekat dengan...',
                'options' => [
                    'A' => 'Penyesuaian',
                    'B' => 'Pemerataan',
                    'C' => 'Percepatan',
                    'D' => 'Pengendalian',
                    'E' => 'Peninjauan',
                ],
                'correct_answer' => 'C',
                'explanation' => 'Akselerasi berakar dari bahasa serapan yang berarti peningkatan kecepatan atau percepatan.',
            ],
            [
                'subtest_code' => 'LBI',
                'question_text' => 'Gagasan pokok yang paling tepat untuk paragraf tentang krisis transisi energi terbarukan di negara berkembang adalah...',
                'options' => [
                    'A' => 'Tingginya harga panel surya di pasar global.',
                    'B' => 'Tantangan pendanaan dan infrastruktur dalam transisi energi di negara berkembang.',
                    'C' => 'Dampak polusi udara terhadap kesehatan masyarakat perkotaan.',
                    'D' => 'Kewajiban negara maju menutup pembangkit batubara.',
                    'E' => 'Keberhasilan mikro-grid di kawasan pedesaan Indonesia.',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Paragraf mengulas dilema biaya modal awal dan kesiapan jaringan listrik di negara berkembang.',
            ],
            [
                'subtest_code' => 'LBS',
                'question_text' => 'What is the primary message of the text regarding the integration of generative AI in education?',
                'options' => [
                    'A' => 'Generative AI should be entirely banned to protect examination integrity.',
                    'B' => 'AI is a dynamic cognitive scaffold that amplifies personalized learning when paired with ethical standards.',
                    'C' => 'Human educators will be rendered redundant by large language models within a decade.',
                    'D' => 'Students should rely solely on automated algorithms for essay composition.',
                    'E' => 'Prompt engineering is the only subject that high schools should teach.',
                ],
                'correct_answer' => 'B',
                'explanation' => 'The passage highlights that AI scaffolds personalized education without replacing foundational human curiosity.',
            ],
        ];

        $createdQuestions = [];
        foreach ($sampleQuestions as $q) {
            $createdQuestions[] = TryoutQuestion::firstOrCreate(
                [
                    'tryout_id' => $tryout1->id,
                    'subtest_code' => $q['subtest_code'],
                    'question_text' => $q['question_text'],
                ],
                [
                    'options' => $q['options'],
                    'correct_answer' => $q['correct_answer'],
                    'explanation' => $q['explanation'],
                    'image_path' => null,
                ]
            );
        }

        // 4. Seed Attempts, Answers & Scores for Students
        $students = Student::all();

        foreach ($students as $student) {
            // Attempt 1: Completed
            $attempt1 = TryoutAttempt::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'tryout_id' => $tryout1->id,
                ],
                [
                    'started_at' => now()->subDays(12)->setHour(8)->setMinute(0),
                    'submitted_at' => now()->subDays(12)->setHour(11)->setMinute(15),
                    'status' => 'completed',
                ]
            );

            foreach ($createdQuestions as $cq) {
                TryoutAnswer::firstOrCreate(
                    [
                        'attempt_id' => $attempt1->id,
                        'question_id' => $cq->id,
                    ],
                    [
                        'student_answer' => $cq->correct_answer,
                        'is_correct' => true,
                    ]
                );
            }

            // Subtest scores JSON
            $subtestScores = [
                'PU' => 680,
                'PPU' => 650,
                'PBM' => 670,
                'PK' => 640,
                'LBI' => 700,
                'LBS' => 660,
                'PM' => 630,
            ];

            TryoutScore::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'tryout_id' => $tryout1->id,
                ],
                [
                    'subtest_tps' => 660.00,
                    'subtest_indo' => 700.00,
                    'subtest_inggris' => 660.00,
                    'subtest_matematika' => 630.00,
                    'subtest_scores' => $subtestScores,
                    'average_score' => 662.50,
                ]
            );

            // Attempt 2: In progress
            TryoutAttempt::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'tryout_id' => $tryout2->id,
                ],
                [
                    'started_at' => now()->subHours(1),
                    'submitted_at' => null,
                    'status' => 'in_progress',
                ]
            );
        }
    }
}
