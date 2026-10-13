<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SpeakingBooking;
use App\Models\Student;
use App\Models\TryoutScore;
use App\Models\User;
use App\Models\VocabQuizResult;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChildProgressController extends Controller
{
    /**
     * Resolve child student for the authenticated parent.
     */
    protected function getChild(User $user, ?int $studentId = null): Student
    {
        $children = $user->children()->with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])->get();

        if ($children->isEmpty()) {
            return Student::with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])->firstOrFail();
        }

        if ($studentId) {
            $child = $children->firstWhere('id', $studentId);
            if ($child) {
                return $child;
            }
        }

        return $children->first();
    }

    /**
     * Display detailed child attendance history.
     */
    public function attendance(Request $request, AttendanceCalculatorService $calculator): View
    {
        /** @var User $user */
        $user = $request->user();
        $studentId = $request->query('student_id');
        $child = $this->getChild($user, $studentId ? (int) $studentId : null);

        $status = $request->query('status');
        $query = Attendance::where('student_id', $child->id)
            ->with('scannedByTeacher.user')
            ->orderBy('date', 'desc')
            ->orderBy('session_time', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $attendances = $query->paginate(15)->withQueryString();
        $stats = $calculator->calculate($child->id);

        $children = $user->children()->get();

        return view('orang_tua.rekap.presensi', compact('child', 'attendances', 'stats', 'children', 'status'));
    }

    /**
     * Display child academic results: Tryout SNBT, Vocab Mastery, and Speaking Exams.
     */
    public function academic(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $studentId = $request->query('student_id');
        $child = $this->getChild($user, $studentId ? (int) $studentId : null);

        // Tryout scores
        $tryoutScores = TryoutScore::with('tryout')
            ->where('student_id', $child->id)
            ->latest()
            ->get();

        // Vocab results across 8 modules
        $vocabResults = VocabQuizResult::where('student_id', $child->id)
            ->orderBy('module_number', 'asc')
            ->get()
            ->keyBy('module_number');

        // Speaking evaluation results
        $speakingBookings = SpeakingBooking::with(['speakingTask', 'mentor.user'])
            ->where('student_id', $child->id)
            ->latest()
            ->get();

        $children = $user->children()->get();

        return view('orang_tua.rekap.hasil-belajar', compact(
            'child',
            'tryoutScores',
            'vocabResults',
            'speakingBookings',
            'children'
        ));
    }
}
