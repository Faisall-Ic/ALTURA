<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::first();
        $teacher = Teacher::first();

        if (! $student || ! $teacher) {
            return;
        }

        $records = [
            ['days_ago' => 14, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 1: Matrikulasi Aljabar'],
            ['days_ago' => 13, 'time' => '16:05:00', 'status' => 'hadir', 'remarks' => 'Sesi 2: Fungsi & Persamaan Kuadrat'],
            ['days_ago' => 12, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 3: Barisan & Deret Aritmatika'],
            ['days_ago' => 11, 'time' => '16:25:00', 'status' => 'terlambat', 'remarks' => 'Terlambat 25 menit karena hujan deras'],
            ['days_ago' => 10, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 5: Logika Matematika & TPS'],
            ['days_ago' => 9, 'time' => '16:02:00', 'status' => 'hadir', 'remarks' => 'Sesi 6: Eksponen & Logaritma'],
            ['days_ago' => 8, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 7: Trigonometri Dasar'],
            ['days_ago' => 7, 'time' => '16:00:00', 'status' => 'izin', 'remarks' => 'Izin kegiatan lomba sekolah'],
            ['days_ago' => 6, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 9: Matriks & Determinan'],
            ['days_ago' => 5, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 10: Limit Fungsi Aljabar'],
            ['days_ago' => 4, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 11: Turunan & Aplikasi Maks/Min'],
            ['days_ago' => 1, 'time' => '16:00:00', 'status' => 'hadir', 'remarks' => 'Sesi 12: Bedah Soal TO Nasional 1'],
        ];

        foreach ($records as $item) {
            $date = Carbon::today()->subDays($item['days_ago'])->toDateString();

            $existing = Attendance::where('student_id', $student->id)
                ->whereDate('date', $date)
                ->where('session_time', $item['time'])
                ->first();

            if (! $existing) {
                Attendance::create([
                    'student_id' => $student->id,
                    'date' => $date,
                    'session_time' => $item['time'],
                    'status' => $item['status'],
                    'scanned_by_teacher_id' => $teacher->id,
                    'remarks' => $item['remarks'],
                ]);
            }
        }
    }
}
