<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\LessonSchedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonScheduleController extends Controller
{
    /**
     * Display a listing of lesson schedules.
     */
    public function index(Request $request): View
    {
        $classId = $request->query('class_id');
        $dayOfWeek = $request->query('day_of_week');
        $subjectId = $request->query('subject_id');

        $schedules = LessonSchedule::with(['academicClass', 'subject', 'teacher.user'])
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($dayOfWeek, fn ($q) => $q->where('day_of_week', $dayOfWeek))
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->orderByRaw("CASE day_of_week 
                WHEN 'Senin' THEN 1 
                WHEN 'Selasa' THEN 2 
                WHEN 'Rabu' THEN 3 
                WHEN 'Kamis' THEN 4 
                WHEN 'Jumat' THEN 5 
                WHEN 'Sabtu' THEN 6 
                ELSE 7 END")
            ->orderBy('time_start')
            ->paginate(15)
            ->withQueryString();

        $classes = AcademicClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.operasional.jadwal.index', compact('schedules', 'classes', 'subjects', 'days', 'classId', 'dayOfWeek', 'subjectId'));
    }

    /**
     * Show form for creating a lesson schedule.
     */
    public function create(): View
    {
        $classes = AcademicClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.operasional.jadwal.create', compact('classes', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created lesson schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:academic_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'room' => ['required', 'string', 'max:50'],
            'day_of_week' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        LessonSchedule::create($validated);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    /**
     * Show form for editing a lesson schedule.
     */
    public function edit(LessonSchedule $jadwal): View
    {
        $classes = AcademicClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.operasional.jadwal.edit', [
            'schedule' => $jadwal,
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'days' => $days,
        ]);
    }

    /**
     * Update the specified lesson schedule.
     */
    public function update(Request $request, LessonSchedule $jadwal): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:academic_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'room' => ['required', 'string', 'max:50'],
            'day_of_week' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified lesson schedule.
     */
    public function destroy(LessonSchedule $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
