<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\HomeworkAssignment;
use App\Models\LessonSchedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student()->with(['major', 'academicClass', 'mentor.user', 'counselor'])->first();

        $isTkaActive = $student?->academicClass?->is_tka_active ?? true;

        // Fetch schedules
        $schedules = collect();
        $assignments = collect();
        $activeSubjects = collect();

        if ($student) {
            $scheduleQuery = LessonSchedule::where('class_id', $student->class_id)
                ->with(['subject', 'teacher.user'])
                ->orderByRaw("CASE day_of_week 
                    WHEN 'Senin' THEN 1 
                    WHEN 'Selasa' THEN 2 
                    WHEN 'Rabu' THEN 3 
                    WHEN 'Kamis' THEN 4 
                    WHEN 'Jumat' THEN 5 
                    WHEN 'Sabtu' THEN 6 
                    ELSE 7 END")
                ->orderBy('time_start');

            // Hide TKA subjects when is_tka_active is false
            if (! $isTkaActive) {
                $scheduleQuery->whereHas('subject', fn ($sq) => $sq->where('is_tka', false));
            }

            $schedules = $scheduleQuery->get();

            $assignments = HomeworkAssignment::where('class_id', $student->class_id)
                ->with(['subject', 'teacher.user'])
                ->latest('due_date')
                ->take(5)
                ->get();

            // Subjects list for this class/major
            $subjectQuery = Subject::where(function ($q) use ($student) {
                $q->where('is_tka', false)
                    ->orWhereHas('majors', fn ($mq) => $mq->where('majors.id', $student->major_id));
            });

            if (! $isTkaActive) {
                $subjectQuery->where('is_tka', false);
            }

            $activeSubjects = $subjectQuery->orderBy('name')->get();
        }

        return view('siswa.dashboard', compact(
            'user',
            'student',
            'isTkaActive',
            'schedules',
            'assignments',
            'activeSubjects'
        ));
    }
}
