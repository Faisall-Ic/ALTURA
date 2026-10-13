<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\WaMessageLog;
use Illuminate\Database\Seeder;

class WaMessageLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::first();
        $phone = $student?->parent_phone_number ?? '6281234567890';
        $studentName = $student?->user?->name ?? 'Ahmad Rizky Pratama';

        WaMessageLog::firstOrCreate(
            [
                'parent_phone_number' => $phone,
                'trigger_event' => 'attendance_checkin',
                'sent_at' => now()->subDay()->setTime(16, 5, 0),
            ],
            [
                'message_payload' => "Halo Bapak/Ibu Wali dari {$studentName},\n\nKami menginformasikan bahwa ananda telah tercatat HADIR pada sesi kelas bimbingan hari ini.\nPersentase Kehadiran Saat Ini: 95.0%.\n\nTim Akademik ALTURA (Alto's System)",
                'status' => 'sent',
            ]
        );

        WaMessageLog::firstOrCreate(
            [
                'parent_phone_number' => $phone,
                'trigger_event' => 'tryout_score_card',
                'sent_at' => now()->subDays(3)->setTime(10, 0, 0),
            ],
            [
                'message_payload' => "🎯 LAPORAN HASIL TRYOUT SNBT ALTURA\n\nHalo Bapak/Ibu Wali dari {$studentName},\nBerikut hasil ujian Tryout Seri 1 ananda: Rata-rata 639.13.\n\nTim Akademik ALTURA",
                'status' => 'sent',
            ]
        );
    }
}
