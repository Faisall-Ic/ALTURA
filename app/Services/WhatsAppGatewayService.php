<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceFlag;
use App\Models\Student;
use App\Models\TryoutScore;
use App\Models\WaMessageLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppGatewayService
{
    /**
     * Send raw WhatsApp message and record log.
     */
    public function sendRaw(string $phoneNumber, string $message, string $triggerEvent): WaMessageLog
    {
        $status = 'sent';
        $errorResponse = null;

        $gatewayUrl = config('services.wa_gateway.url', env('WA_GATEWAY_URL'));
        $apiKey = config('services.wa_gateway.api_key', env('WA_GATEWAY_API_KEY'));

        if (! empty($gatewayUrl) && ! empty($apiKey)) {
            try {
                $response = Http::timeout(5)
                    ->withHeaders(['Authorization' => "Bearer {$apiKey}"])
                    ->post($gatewayUrl, [
                        'phone' => $phoneNumber,
                        'message' => $message,
                    ]);

                if (! $response->successful()) {
                    $status = 'failed';
                    $errorResponse = $response->body();
                }
            } catch (\Throwable $e) {
                $status = 'failed';
                $errorResponse = $e->getMessage();
                Log::warning("WhatsApp Gateway Error: {$e->getMessage()}");
            }
        }

        return WaMessageLog::create([
            'parent_phone_number' => $phoneNumber,
            'message_payload' => $message,
            'trigger_event' => $triggerEvent,
            'status' => $status,
            'error_response' => $errorResponse,
            'sent_at' => now(),
        ]);
    }

    /**
     * Dispatch automated attendance notification to parent.
     */
    public function sendAttendanceNotification(Student $student, Attendance $attendance, float $percentage): WaMessageLog
    {
        $studentName = $student->user?->name ?? 'Siswa';
        $statusUpper = strtoupper($attendance->status);
        $dateFormatted = Carbon::parse($attendance->date)->translatedFormat('d F Y');
        $timeFormatted = substr($attendance->session_time, 0, 5);

        $message = "Halo Bapak/Ibu Wali dari {$studentName},\n\n"
            ."Kami menginformasikan bahwa ananda telah tercatat *{$statusUpper}* pada sesi kelas bimbingan:\n"
            ."📅 Tanggal: {$dateFormatted}\n"
            ."⏰ Waktu: {$timeFormatted} WIB\n"
            ."📊 Persentase Kehadiran Saat Ini: {$percentage}%\n\n"
            ."Pantau rekapitulasi lengkap & grafik belajar ananda melalui Portal Orang Tua ALTURA:\n"
            .url('/ortu/dashboard')."\n\n"
            ."Salam hangat,\n"
            ."*Tim Akademik ALTURA (Alto's System)*";

        return $this->sendRaw(
            $student->parent_phone_number,
            $message,
            'attendance_checkin'
        );
    }

    /**
     * Dispatch critical attendance / academic alert to parent.
     */
    public function sendFlagAlert(Student $student, AttendanceFlag $flag): WaMessageLog
    {
        $studentName = $student->user?->name ?? 'Siswa';
        $flagTypeLabels = [
            'late_series' => 'Keterlambatan Berturut-turut (≥ 3 kali)',
            'absence_critical' => 'Peringatan Ketidakhadiran Kritis (< 75% / Alfa)',
            'vocab_behind' => 'Target Setoran Kosakata Tertinggal',
        ];
        $flagLabel = $flagTypeLabels[$flag->flag_type] ?? strtoupper($flag->flag_type);

        $message = "🚨 *PEMBERITAHUAN PENTING ALTURA*\n\n"
            ."Yth. Bapak/Ibu Wali dari {$studentName},\n"
            ."Sistem monitoring kami mendeteksi indikator yang memerlukan perhatian khusus:\n\n"
            ."⚠️ Jenis Indikator: *{$flagLabel}*\n"
            ."📝 Keterangan: {$flag->reason}\n\n"
            ."Guru Pembimbing & Guru BK kami siap berdiskusi untuk mencari solusi belajar terbaik. Silakan jadwalkan sesi konsultasi gratis melalui Portal Orang Tua:\n"
            .url('/ortu/konsultasi/booking-bk')."\n\n"
            .'*Bimbingan Konseling ALTURA Bimbel*';

        return $this->sendRaw(
            $student->parent_phone_number,
            $message,
            'critical_flag_alert'
        );
    }

    /**
     * Dispatch Tryout score card to parent.
     */
    public function sendTryoutScoreCard(Student $student, TryoutScore $score): WaMessageLog
    {
        $studentName = $student->user?->name ?? 'Siswa';
        $tryoutTitle = $score->tryout?->title ?? 'Tryout SNBT Alto';

        $message = "🎯 *LAPORAN HASIL TRYOUT SNBT ALTURA*\n\n"
            ."Halo Bapak/Ibu Wali dari {$studentName},\n"
            ."Berikut adalah rekapitulasi nilai ujian *{$tryoutTitle}* ananda:\n\n"
            ."🔹 Rata-rata Total: *{$score->average_score}*\n"
            ."  • TPS & Penalaran: {$score->subtest_tps}\n"
            ."  • Literasi B. Indonesia: {$score->subtest_indo}\n"
            ."  • Literasi B. Inggris: {$score->subtest_inggris}\n"
            ."  • Penalaran Matematika: {$score->subtest_matematika}\n\n"
            ."Laporan detail dan analisis radar potensi kelulusan PTN dapat diunduh di Portal Orang Tua:\n"
            .url('/ortu/rekap/hasil-belajar')."\n\n"
            .'*Tim Akademik ALTURA*';

        return $this->sendRaw(
            $student->parent_phone_number,
            $message,
            'tryout_score_card'
        );
    }
}
