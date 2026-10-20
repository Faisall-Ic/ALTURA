<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\LessonSchedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapelController extends Controller
{
    /**
     * Display Subject Teacher View (Peran: Guru Mapel).
     * Provides read-only class attendance for schedule sessions and class student list.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        // Taught subjects & classes
        $mySubjects = $teacher?->subjects ?? collect();
        $myClasses = $teacher?->classes ?? collect();

        $selectedSubjectId = (int) ($request->query('subject_id') ?: ($mySubjects->first()?->id ?? 0));
        $selectedClassId = (int) ($request->query('class_id') ?: ($myClasses->first()?->id ?? 0));

        $activeSubject = Subject::find($selectedSubjectId);
        $activeClass = AcademicClass::find($selectedClassId);

        // Lesson schedules for this teacher & class/subject
        $schedules = LessonSchedule::where('teacher_id', $teacher->id)
            ->when($selectedSubjectId, fn ($q) => $q->where('subject_id', $selectedSubjectId))
            ->when($selectedClassId, fn ($q) => $q->where('class_id', $selectedClassId))
            ->get();

        // Students in the selected class
        $students = collect();
        $attendancesToday = collect();

        if ($activeClass) {
            $students = Student::with('user')
                ->where('class_id', $activeClass->id)
                ->where('status_active', true)
                ->get();

            $attendancesToday = Attendance::whereIn('student_id', $students->pluck('id'))
                ->whereDate('date', now()->toDateString())
                ->get()
                ->keyBy('student_id');
        }

        return view('guru.mapel.index', compact(
            'teacher',
            'mySubjects',
            'myClasses',
            'selectedSubjectId',
            'selectedClassId',
            'activeSubject',
            'activeClass',
            'schedules',
            'students',
            'attendancesToday'
        ));
    }
}
