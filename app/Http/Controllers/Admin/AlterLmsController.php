<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlterChapter;
use App\Models\AlterCourse;
use App\Models\AlterProblem;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlterLmsController extends Controller
{
    /**
     * Display a listing of ALTER LMS courses.
     */
    public function index(Request $request): View
    {
        $subjectId = $request->query('subject_id');
        $search = $request->query('q');

        $courses = AlterCourse::with(['subject', 'chapters.problems'])
            ->withCount('chapters')
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        $subjects = Subject::orderBy('name')->get();

        return view('admin.konten.alter.index', compact('courses', 'subjects', 'subjectId', 'search'));
    }

    /**
     * Show form for creating a new ALTER course.
     */
    public function createCourse(): View
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.konten.alter.course-create', compact('subjects'));
    }

    /**
     * Store a newly created ALTER course.
     */
    public function storeCourse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_snbt_core' => ['nullable'],
        ]);

        $validated['is_snbt_core'] = $request->boolean('is_snbt_core', true);

        $course = AlterCourse::create($validated);

        return redirect()->route('admin.alter.show', $course->id)
            ->with('success', "Modul ALTER '{$course->title}' berhasil dibuat. Silakan tambahkan chapter video & soal.");
    }

    /**
     * Display detail of an ALTER course, its chapters, and problems.
     */
    public function showCourse(AlterCourse $course): View
    {
        $course->load(['subject', 'chapters.problems']);

        return view('admin.konten.alter.show', compact('course'));
    }

    /**
     * Show form for editing an ALTER course.
     */
    public function editCourse(AlterCourse $course): View
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.konten.alter.course-edit', compact('course', 'subjects'));
    }

    /**
     * Update an ALTER course.
     */
    public function updateCourse(Request $request, AlterCourse $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_snbt_core' => ['nullable'],
        ]);

        $validated['is_snbt_core'] = $request->boolean('is_snbt_core');

        $course->update($validated);

        return redirect()->route('admin.alter.show', $course->id)
            ->with('success', 'Data modul ALTER berhasil diperbarui.');
    }

    /**
     * Delete an ALTER course and all related chapters and problems.
     */
    public function destroyCourse(AlterCourse $course): RedirectResponse
    {
        foreach ($course->chapters as $chapter) {
            $chapter->problems()->delete();
        }
        $course->chapters()->delete();
        $course->delete();

        return redirect()->route('admin.alter.index')
            ->with('success', 'Modul ALTER berhasil dihapus.');
    }

    /**
     * Store a new chapter under a course.
     */
    public function storeChapter(Request $request, AlterCourse $course): RedirectResponse
    {
        $validated = $request->validate([
            'chapter_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:200'],
            'youtube_video_id' => ['required', 'string', 'max:50'],
        ]);

        $course->chapters()->create($validated);

        return back()->with('success', "Chapter {$validated['chapter_number']} berhasil ditambahkan.");
    }

    /**
     * Update an existing chapter.
     */
    public function updateChapter(Request $request, AlterChapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'chapter_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:200'],
            'youtube_video_id' => ['required', 'string', 'max:50'],
        ]);

        $chapter->update($validated);

        return back()->with('success', "Chapter {$chapter->chapter_number} berhasil diperbarui.");
    }

    /**
     * Delete a chapter.
     */
    public function destroyChapter(AlterChapter $chapter): RedirectResponse
    {
        $chapter->problems()->delete();
        $chapter->delete();

        return back()->with('success', 'Chapter berhasil dihapus.');
    }

    /**
     * Store a problem under a chapter.
     */
    public function storeProblem(Request $request, AlterChapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'problem_number' => ['required', 'integer', 'min:1'],
            'type_name' => ['required', 'string', 'max:50'],
            'problem_image_path' => ['nullable', 'string', 'max:255'],
        ]);

        $chapter->problems()->create($validated);

        return back()->with('success', "Soal Latihan No. {$validated['problem_number']} berhasil ditambahkan.");
    }

    /**
     * Delete a problem.
     */
    public function destroyProblem(AlterProblem $problem): RedirectResponse
    {
        $problem->delete();

        return back()->with('success', 'Soal latihan berhasil dihapus.');
    }
}
