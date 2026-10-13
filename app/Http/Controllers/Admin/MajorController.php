<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MajorController extends Controller
{
    /**
     * Display a listing of the majors.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $majors = Major::withCount(['academicClasses', 'students'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.master.jurusan.index', compact('majors', 'search'));
    }

    /**
     * Show the form for creating a new major.
     */
    public function create(): View
    {
        return view('admin.master.jurusan.create');
    }

    /**
     * Store a newly created major in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:majors,code'],
            'is_snbt_mandatory' => ['nullable', 'boolean'],
        ]);

        $validated['is_snbt_mandatory'] = $request->boolean('is_snbt_mandatory');

        Major::create($validated);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan akademik berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified major.
     */
    public function edit(Major $jurusan): View
    {
        return view('admin.master.jurusan.edit', ['major' => $jurusan]);
    }

    /**
     * Update the specified major in storage.
     */
    public function update(Request $request, Major $jurusan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:majors,code,'.$jurusan->id],
            'is_snbt_mandatory' => ['nullable', 'boolean'],
        ]);

        $validated['is_snbt_mandatory'] = $request->boolean('is_snbt_mandatory');

        $jurusan->update($validated);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Data jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified major from storage.
     */
    public function destroy(Major $jurusan): RedirectResponse
    {
        if ($jurusan->academicClasses()->exists() || $jurusan->students()->exists()) {
            return redirect()->route('admin.jurusan.index')
                ->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki kelas atau siswa terdaftar.');
        }

        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
