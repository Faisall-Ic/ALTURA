<?php

namespace App\Services;

use App\Models\Attendance;

class AttendanceCalculatorService
{
    /**
     * Calculate attendance statistics and health code for a given student.
     *
     * @return array{
     *     total: int,
     *     hadir: int,
     *     terlambat: int,
     *     sakit: int,
     *     izin: int,
     *     alfa: int,
     *     percentage: float,
     *     color: string,
     *     status_label: string
     * }
     */
    public function calculate(int $studentId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Attendance::where('student_id', $studentId);

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $records = $query->get();
        $total = $records->count();

        $hadir = $records->where('status', 'hadir')->count();
        $terlambat = $records->where('status', 'terlambat')->count();
        $sakit = $records->where('status', 'sakit')->count();
        $izin = $records->where('status', 'izin')->count();
        $alfa = $records->where('status', 'alfa')->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'hadir' => 0,
                'terlambat' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alfa' => 0,
                'percentage' => 100.0,
                'color' => 'green',
                'status_label' => 'Normal',
            ];
        }

        // Attendance rate considers hadir + terlambat as attended
        $effectiveAttended = $hadir + $terlambat;
        $percentage = round(($effectiveAttended / $total) * 100, 2);

        if ($percentage >= 90.0) {
            $color = 'green';
            $statusLabel = 'Normal';
        } elseif ($percentage >= 75.0) {
            $color = 'yellow';
            $statusLabel = 'Warning';
        } else {
            $color = 'red';
            $statusLabel = 'Kritis';
        }

        return [
            'total' => $total,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'sakit' => $sakit,
            'izin' => $izin,
            'alfa' => $alfa,
            'percentage' => $percentage,
            'color' => $color,
            'status_label' => $statusLabel,
        ];
    }
}
