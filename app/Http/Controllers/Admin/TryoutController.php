<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
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
        $tryouts = Tryout::withCount('tryoutScores')
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
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date', 'after_or_equal:date_start'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Tryout::create([
            'title' => $validated['title'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tryout.index')->with('success', 'Paket Tryout berhasil dibuat.');
    }

    /**
     * Update the specified tryout package.
     */
    public function update(Request $request, Tryout $tryout): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date', 'after_or_equal:date_start'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $tryout->update([
            'title' => $validated['title'],
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
        $tryout->delete();

        return redirect()->route('admin.tryout.index')->with('success', 'Paket Tryout berhasil dihapus.');
    }
}
