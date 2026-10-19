<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\MonthlySubmissionAlert;
use App\Models\StorytellingTheme;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\WacanaText;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembimbingController extends Controller
{
    /**
     * Display 360-Degree Holistic Dashboard for Pembimbing Akademik (Mentor).
     * Recaps Vocab, Storytelling, Wacana, ALTER, and Attendance for assigned mentees.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $currentMonth = (int) now()->format('m');
        $currentYear = (int) now()->format('Y');

        // Mentees assigned to this teacher
        $mentees = Student::with([
            'user',
            'academicClass',
            'major',
            'vocabQuizResults' => fn ($q) => $q->whereYear('completed_at', $currentYear)->whereMonth('completed_at', $currentMonth),
            'storytellingSubmissions' => fn ($q) => $q->whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth),
            'wacanaSubmissions' => fn ($q) => $q->whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth),
            'speakingBookings' => fn ($q) => $q->whereYear('booking_date', $currentYear)->whereMonth('booking_date', $currentMonth),
        ])
            ->where('mentor_id', $teacher->id)
            ->get();

        // Calculate attendance health for each mentee
        $calculator = app(AttendanceCalculatorService::class);
        $menteeStats = [];

        foreach ($mentees as $mentee) {
            $stats = $calculator->calculate($mentee->id);
            $hasVocab = $mentee->vocabQuizResults->isNotEmpty();
            $hasStorytelling = $mentee->storytellingSubmissions->isNotEmpty();
            $wacanaApplies = WacanaText::appliesToStudent($mentee);
            $hasWacana = $wacanaApplies ? $mentee->wacanaSubmissions->isNotEmpty() : true;
            $hasOral = $mentee->speakingBookings->isNotEmpty();

            $completedCount = ($hasVocab ? 1 : 0) + ($hasStorytelling ? 1 : 0) + ($hasWacana ? 1 : 0) + ($hasOral ? 1 : 0);
            $totalTarget = $wacanaApplies ? 4 : 3;

            $menteeStats[$mentee->id] = [
                'attendance_pct' => $stats['percentage'],
                'attendance_color' => $stats['color'],
                'has_vocab' => $hasVocab,
                'has_storytelling' => $hasStorytelling,
                'has_wacana' => $hasWacana,
                'wacana_applies' => $wacanaApplies,
                'has_oral' => $hasOral,
                'progress_pct' => round(($completedCount / $totalTarget) * 100),
            ];
        }

        // Active monthly submission alerts for this mentor
        $alerts = MonthlySubmissionAlert::with('student.user')
            ->where('mentor_id', $teacher->id)
            ->where('is_resolved', false)
            ->latest()
            ->get();

        $activeTheme = StorytellingTheme::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        return view('guru.pembimbing.index', compact(
            'teacher',
            'mentees',
            'menteeStats',
            'alerts',
            'activeTheme',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Trigger Tanggal 20 Auto-Alert evaluation across all mentees.
     */
    public function triggerMonthlyAlerts(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $month = (int) $request->input('month', now()->format('m'));
        $year = (int) $request->input('year', now()->format('Y'));

        $mentees = Student::where('mentor_id', $teacher->id)->get();
        $generatedCount = 0;

        foreach ($mentees as $mentee) {
            $alert = MonthlySubmissionAlert::evaluateMonthlySubmissions($mentee, $month, $year);
            if ($alert && $alert->wasRecentlyCreated) {
                $generatedCount++;
            }
        }

        return redirect()->route('guru.pembimbing.index')
            ->with('success', "Pemeriksaan tanggal 20 selesai. {$generatedCount} alert tugas belum terpenuhi berhasil diperbarui.");
    }

    /**
     * Mark a monthly submission alert as resolved.
     */
    public function resolveAlert(int $alertId): RedirectResponse
    {
        $alert = MonthlySubmissionAlert::findOrFail($alertId);
        $alert->update(['is_resolved' => true]);

        return redirect()->route('guru.pembimbing.index')
            ->with('success', 'Alert penugasan siswa berhasil ditandai selesai.');
    }
}
