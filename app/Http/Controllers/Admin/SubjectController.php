<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects.
     */
    public function index(Request $request): View
    {
        $subjects = Subject::with('majors')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->filled('type'), function ($q) use ($request) {
                if ($request->input('type') === 'tka') {
                    $q->where('is_tka', true);
                } elseif ($request->input('type') === 'snbt') {
                    $q->where('is_tka', false);
                }
            })
            ->orderBy('is_tka', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.master.mata_pelajaran.index', compact('subjects'));
    }

    /**
     * Show form for creating a new subject.
     */
    public function create(): View
    {
        $majors = Major::orderBy('name')->get();

        return view('admin.master.mata_pelajaran.create', compact('majors'));
    }

    /**
     * Store a newly created subject in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:subjects,code',
            'is_tka' => 'required|boolean',
            'description' => 'nullable|string|max:500',
            'major_ids' => 'nullable|array',
            'major_ids.*' => 'exists:majors,id',
        ]);

        $subject = Subject::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'is_tka' => (bool) $validated['is_tka'],
            'description' => $validated['description'] ?? null,
        ]);

        if (! empty($validated['major_ids'])) {
            $subject->majors()->sync($validated['major_ids']);
        }

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', "Mata Pelajaran {$subject->name} berhasil didaftarkan.");
    }

    /**
     * Show form for editing a subject.
     */
    public function edit(Subject $mataPelajaran): View
    {
        $subject = $mataPelajaran;
        $majors = Major::orderBy('name')->get();
        $selectedMajors = $subject->majors->pluck('id')->toArray();

        return view('admin.master.mata_pelajaran.edit', compact('subject', 'majors', 'selectedMajors'));
    }

    /**
     * Update the specified subject in database.
     */
    public function update(Request $request, Subject $mataPelajaran): RedirectResponse
    {
        $subject = $mataPelajaran;

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:subjects,code,'.$subject->id,
            'is_tka' => 'required|boolean',
            'description' => 'nullable|string|max:500',
            'major_ids' => 'nullable|array',
            'major_ids.*' => 'exists:majors,id',
        ]);

        $subject->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'is_tka' => (bool) $validated['is_tka'],
            'description' => $validated['description'] ?? null,
        ]);

        $subject->majors()->sync($validated['major_ids'] ?? []);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', "Mata Pelajaran {$subject->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified subject from database.
     */
    public function destroy(Subject $mataPelajaran): RedirectResponse
    {
        $subject = $mataPelajaran;
        $name = $subject->name;
        $subject->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', "Mata Pelajaran {$name} berhasil dihapus.");
    }
}
