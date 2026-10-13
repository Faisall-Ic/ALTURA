<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Major;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicClassController extends Controller
{
    /**
     * Display a listing of the academic classes.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $majorId = $request->query('major_id');

        $classes = AcademicClass::with('major')
            ->withCount('students')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($majorId, function ($query, $majorId) {
                $query->where('major_id', $majorId);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $majors = Major::orderBy('name')->get();

        return view('admin.master.kelas.index', compact('classes', 'majors', 'search', 'majorId'));
    }

    /**
     * Show the form for creating a new academic class.
     */
    public function create(): View
    {
        $majors = Major::orderBy('name')->get();

        return view('admin.master.kelas.create', compact('majors'));
    }

    /**
     * Store a newly created academic class in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'major_id' => ['required', 'exists:majors,id'],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:academic_classes,code'],
        ]);

        AcademicClass::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Rombongan belajar (kelas) berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified academic class.
     */
    public function edit(AcademicClass $kela): View
    {
        $majors = Major::orderBy('name')->get();

        return view('admin.master.kelas.edit', [
            'class' => $kela,
            'majors' => $majors,
        ]);
    }

    /**
     * Update the specified academic class in storage.
     */
    public function update(Request $request, AcademicClass $kela): RedirectResponse
    {
        $validated = $request->validate([
            'major_id' => ['required', 'exists:majors,id'],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:academic_classes,code,'.$kela->id],
        ]);

        $kela->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data rombongan belajar berhasil diperbarui.');
    }

    /**
     * Remove the specified academic class from storage.
     */
    public function destroy(AcademicClass $kela): RedirectResponse
    {
        if ($kela->students()->exists()) {
            return redirect()->route('admin.kelas.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.');
        }

        $kela->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
