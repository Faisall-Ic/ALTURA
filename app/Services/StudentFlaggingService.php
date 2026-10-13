<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceFlag;
use App\Models\Student;
use App\Models\VocabQuizResult;

class StudentFlaggingService
{
    public function __construct(
        protected AttendanceCalculatorService $calculator,
        protected WhatsAppGatewayService $waGateway,
    ) {}

    /**
     * Check if student has 3 consecutive late attendances.
     */
    public function checkConsecutiveLates(Student $student): ?AttendanceFlag
    {
        $recentAttendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->orderBy('session_time', 'desc')
            ->take(3)
            ->get();

        if ($recentAttendances->count() === 3 && $recentAttendances->every(fn ($a) => $a->status === 'terlambat')) {
            $existing = AttendanceFlag::where('student_id', $student->id)
                ->where('flag_type', 'late_series')
                ->whereIn('status', ['open', 'in_progress'])
                ->first();

            if (! $existing) {
                return AttendanceFlag::create([
                    'student_id' => $student->id,
                    'flag_type' => 'late_series',
                    'reason' => 'Siswa tercatat terlambat dalam 3 sesi kehadiran berturut-turut.',
                    'status' => 'open',
                ]);
            }

            return $existing;
        }

        return null;
    }

    /**
     * Check if student has critical absences (2 consecutive alfas or attendance rate < 75%).
     */
    public function checkCriticalAbsences(Student $student): ?AttendanceFlag
    {
        $recentAttendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->orderBy('session_time', 'desc')
            ->take(2)
            ->get();

        $twoConsecutiveAlfas = ($recentAttendances->count() >= 2 && $recentAttendances->every(fn ($a) => $a->status === 'alfa'));

        $stats = $this->calculator->calculate($student->id);
        $rateCritical = ($stats['total'] >= 3 && $stats['percentage'] < 75.0);

        if ($twoConsecutiveAlfas || $rateCritical) {
            $existing = AttendanceFlag::where('student_id', $student->id)
                ->where('flag_type', 'absence_critical')
                ->whereIn('status', ['open', 'in_progress'])
                ->first();

            if (! $existing) {
                $reason = $twoConsecutiveAlfas
                    ? 'Siswa tidak hadir (alfa) dalam 2 sesi pertemuan berturut-turut.'
                    : "Persentase kehadiran berada pada level kritis ({$stats['percentage']}%, di bawah batas minimum 75%).";

                return AttendanceFlag::create([
                    'student_id' => $student->id,
                    'flag_type' => 'absence_critical',
                    'reason' => $reason,
                    'status' => 'open',
                ]);
            }

            return $existing;
        }

        return null;
    }

    /**
     * Check if student is falling behind on vocabulary mastery modules.
     */
    public function checkVocabBehind(Student $student): ?AttendanceFlag
    {
        $passedQuizzesCount = VocabQuizResult::where('student_id', $student->id)
            ->where('is_passed', true)
            ->count();

        // If student has 0 passed modules and student has been registered
        if ($passedQuizzesCount === 0) {
            $existing = AttendanceFlag::where('student_id', $student->id)
                ->where('flag_type', 'vocab_behind')
                ->whereIn('status', ['open', 'in_progress'])
                ->first();

            if (! $existing) {
                return AttendanceFlag::create([
                    'student_id' => $student->id,
                    'flag_type' => 'vocab_behind',
                    'reason' => 'Siswa belum menyelesaikan target kelulusan modul hafalan kosakata (Vocab Mastery).',
                    'status' => 'open',
                ]);
            }

            return $existing;
        }

        return null;
    }

    /**
     * Evaluate all flags for a student and optionally notify parent.
     *
     * @return array<string, AttendanceFlag|null>
     */
    public function evaluateStudent(Student $student, bool $sendNotification = true): array
    {
        $lateFlag = $this->checkConsecutiveLates($student);
        $absenceFlag = $this->checkCriticalAbsences($student);
        $vocabFlag = $this->checkVocabBehind($student);

        $flags = [
            'late_series' => $lateFlag,
            'absence_critical' => $absenceFlag,
            'vocab_behind' => $vocabFlag,
        ];

        if ($sendNotification) {
            foreach ($flags as $flag) {
                if ($flag && $flag->status === 'open' && $flag->wasRecentlyCreated) {
                    $this->waGateway->sendFlagAlert($student, $flag);
                }
            }
        }

        return $flags;
    }

    /**
     * Batch evaluate all active students.
     */
    public function evaluateAllStudents(bool $sendNotification = false): int
    {
        $students = Student::where('status_active', true)->get();
        $totalFlagsRaised = 0;

        foreach ($students as $student) {
            $evaluated = $this->evaluateStudent($student, $sendNotification);
            foreach ($evaluated as $flag) {
                if ($flag && $flag->wasRecentlyCreated) {
                    $totalFlagsRaised++;
                }
            }
        }

        return $totalFlagsRaised;
    }
}
