<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutSubtest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TryoutController extends Controller
{
    /**
     * Display a listing of tryout packages.
     */
    public function index(Request $request): View
    {
        $tryouts = Tryout::with(['subtests'])
            ->withCount('tryoutScores')
            ->latest('date_start')
            ->paginate(10);

        return view('admin.tryout.index', compact('tryouts'));
    }

    /**
     * Store a newly created tryout package.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'batch_name' => ['nullable', 'string', 'max:50'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date', 'after_or_equal:date_start'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $tryout = Tryout::create([
            'title' => $validated['title'],
            'batch_name' => ($validated['batch_name'] ?? null) ?: 'Batch Reguler',
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Auto-seed default 4 SNBT subtests if none provided
        $defaultSubtests = [
            ['name' => 'Tes Potensi Skolastik (TPS)', 'question_count' => 30, 'duration_minutes' => 45],
            ['name' => 'Literasi Bahasa Indonesia', 'question_count' => 25, 'duration_minutes' => 30],
            ['name' => 'Literasi Bahasa Inggris', 'question_count' => 20, 'duration_minutes' => 30],
            ['name' => 'Penalaran Matematika', 'question_count' => 20, 'duration_minutes' => 30],
        ];

        foreach ($defaultSubtests as $st) {
            $tryout->subtests()->create($st);
        }

        return redirect()->route('admin.tryout.index')->with('success', 'Paket Tryout beserta 4 subtes SNBT standar berhasil dibuat.');
    }

    /**
     * Add a subtest to a tryout.
     */
    public function storeSubtest(Request $request, Tryout $tryout): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'question_count' => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        $tryout->subtests()->create($validated);

        return back()->with('success', "Subtes '{$validated['name']}' berhasil ditambahkan ke paket tryout.");
    }

    /**
     * Delete a subtest.
     */
    public function destroySubtest(TryoutSubtest $subtest): RedirectResponse
    {
        $subtest->delete();

        return back()->with('success', 'Subtes berhasil dihapus.');
    }

    /**
     * Update the specified tryout package.
     */
    public function update(Request $request, Tryout $tryout): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'batch_name' => ['nullable', 'string', 'max:50'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date', 'after_or_equal:date_start'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $tryout->update([
            'title' => $validated['title'],
            'batch_name' => ($validated['batch_name'] ?? null) ?: $tryout->batch_name ?: 'Batch Reguler',
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tryout.index')->with('success', 'Paket Tryout berhasil diperbarui.');
    }

    /**
     * Remove the specified tryout package.
     */
    public function destroy(Tryout $tryout): RedirectResponse
    {
        $tryout->subtests()->delete();
        $tryout->tryoutScores()->delete();
        $tryout->delete();

        return redirect()->route('admin.tryout.index')->with('success', 'Paket Tryout berhasil dihapus.');
    }
}
