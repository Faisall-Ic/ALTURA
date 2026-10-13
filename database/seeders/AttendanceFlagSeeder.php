<?php

namespace Database\Seeders;

use App\Models\AttendanceFlag;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::first();
        if (! $student) {
            return;
        }

        $counselor = User::role('guru_bk')->first();

        // 1. Open late_series flag
        AttendanceFlag::firstOrCreate(
            [
                'student_id' => $student->id,
                'flag_type' => 'late_series',
                'status' => 'open',
            ],
            [
                'reason' => 'Siswa tercatat terlambat 3 kali berturut-turut pada sesi pagi.',
                'created_at' => now()->subDays(2),
            ]
        );

        // 2. Open vocab_behind flag
        AttendanceFlag::firstOrCreate(
            [
                'student_id' => $student->id,
                'flag_type' => 'vocab_behind',
                'status' => 'open',
            ],
            [
                'reason' => 'Siswa belum menyelesaikan target kuis modul 4 Academic Vocabulary.',
                'created_at' => now()->subDay(),
            ]
        );

        // 3. Resolved absence_critical flag
        if ($counselor) {
            AttendanceFlag::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'flag_type' => 'absence_critical',
                    'status' => 'resolved',
                ],
                [
                    'reason' => 'Ketidakhadiran 2 sesi tanpa keterangan pekan lalu.',
                    'resolved_by_counselor_id' => $counselor->id,
                    'resolution_notes' => 'Sudah dikonfirmasi dengan wali murid Bapak Bambang. Ananda sakit demam dan surat dokter telah diunggah.',
                    'resolved_at' => now()->subDays(3),
                    'created_at' => now()->subDays(5),
                ]
            );
        }
    }
}
