<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\HomeworkAssignment;
use App\Models\HomeworkSubmission;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeworkAssignmentController extends Controller
{
    /**
     * Display a listing of homework assignments.
     */
    public function index(Request $request): View
    {
        $classId = $request->query('class_id');
        $subjectId = $request->query('subject_id');
        $search = $request->query('q');

        $assignments = HomeworkAssignment::with(['academicClass', 'subject', 'teacher.user'])
            ->withCount('submissions')
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($search, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('instructions', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $classes = AcademicClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.operasional.tugas.index', compact('assignments', 'classes', 'subjects', 'classId', 'subjectId', 'search'));
    }

    /**
     * Show the form for creating a new homework assignment.
     */
    public function create(): View
    {
        $classes = AcademicClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.operasional.tugas.create', compact('classes', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created homework assignment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:academic_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'title' => ['required', 'string', 'max:200'],
            'instructions' => ['required', 'string'],
            'due_date' => ['required', 'date'],
        ]);

        HomeworkAssignment::create($validated);

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas rumah (homework) berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified homework assignment.
     */
    public function edit(HomeworkAssignment $tuga): View
    {
        $classes = AcademicClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.operasional.tugas.edit', [
            'assignment' => $tuga,
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Update the specified homework assignment.
     */
    public function update(Request $request, HomeworkAssignment $tuga): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:academic_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'title' => ['required', 'string', 'max:200'],
            'instructions' => ['required', 'string'],
            'due_date' => ['required', 'date'],
        ]);

        $tuga->update($validated);

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas rumah berhasil diperbarui.');
    }

    /**
     * Remove the specified homework assignment.
     */
    public function destroy(HomeworkAssignment $tuga): RedirectResponse
    {
        $tuga->submissions()->delete();
        $tuga->delete();

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas rumah dan data pengumpulan berhasil dihapus.');
    }

    /**
     * Display student submissions for a specific assignment.
     */
    public function submissions(HomeworkAssignment $tuga): View
    {
        $assignment = $tuga->load(['academicClass', 'subject', 'teacher.user']);
        $submissions = HomeworkSubmission::with(['student.user'])
            ->where('assignment_id', $assignment->id)
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.operasional.tugas.submissions', compact('assignment', 'submissions'));
    }

    /**
     * Grade a student submission.
     */
    public function gradeSubmission(Request $request, HomeworkSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:500'],
        ]);

        $submission->update($validated);

        return back()->with('success', 'Nilai dan umpan balik tugas berhasil disimpan.');
    }
}
