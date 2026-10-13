<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorytellingScript;
use App\Models\VocabModule;
use App\Models\VocabWord;
use App\Models\WacanaScript;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnglishMasteryController extends Controller
{
    /**
     * Display a unified management page for English Mastery (Vocab, Storytelling, Wacana).
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'vocab');
        $moduleNumber = $request->query('module', 1);

        $modules = VocabModule::withCount('words')->orderBy('module_number')->get();
        $vocabWords = VocabWord::where('module_number', $moduleNumber)
            ->latest('id')
            ->paginate(15, ['*'], 'vocab_page')
            ->withQueryString();

        $storytellings = StorytellingScript::latest()->get();
        $wacanas = WacanaScript::latest()->get();

        return view('admin.konten.english.index', compact(
            'activeTab',
            'moduleNumber',
            'modules',
            'vocabWords',
            'storytellings',
            'wacanas'
        ));
    }

    /**
     * Store a new vocabulary word.
     */
    public function storeVocabWord(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module_number' => ['required', 'integer', 'min:1', 'max:20'],
            'english_word' => ['required', 'string', 'max:100'],
            'indonesian_meaning' => ['required', 'string', 'max:255'],
            'example_sentence' => ['nullable', 'string', 'max:500'],
        ]);

        VocabWord::create($validated);

        return redirect()->route('admin.english.index', ['tab' => 'vocab', 'module' => $validated['module_number']])
            ->with('success', "Kosakata '{$validated['english_word']}' berhasil ditambahkan ke Module {$validated['module_number']}.");
    }

    /**
     * Delete a vocabulary word.
     */
    public function destroyVocabWord(VocabWord $word): RedirectResponse
    {
        $moduleNumber = $word->module_number;
        $word->delete();

        return redirect()->route('admin.english.index', ['tab' => 'vocab', 'module' => $moduleNumber])
            ->with('success', 'Kata kosakata berhasil dihapus.');
    }

    /**
     * Store a new storytelling script.
     */
    public function storeStorytelling(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'script_text' => ['required', 'string'],
            'target_duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        StorytellingScript::create($validated);

        return redirect()->route('admin.english.index', ['tab' => 'storytelling'])
            ->with('success', "Naskah Storytelling '{$validated['title']}' berhasil ditambahkan.");
    }

    /**
     * Update an existing storytelling script.
     */
    public function updateStorytelling(Request $request, StorytellingScript $script): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'script_text' => ['required', 'string'],
            'target_duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        $script->update($validated);

        return redirect()->route('admin.english.index', ['tab' => 'storytelling'])
            ->with('success', 'Naskah Storytelling berhasil diperbarui.');
    }

    /**
     * Delete a storytelling script.
     */
    public function destroyStorytelling(StorytellingScript $script): RedirectResponse
    {
        $script->delete();

        return redirect()->route('admin.english.index', ['tab' => 'storytelling'])
            ->with('success', 'Naskah Storytelling berhasil dihapus.');
    }

    /**
     * Store a new wacana text script.
     */
    public function storeWacana(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'script_text' => ['required', 'string'],
            'paragraph_count' => ['required', 'integer', 'min:1'],
        ]);

        WacanaScript::create($validated);

        return redirect()->route('admin.english.index', ['tab' => 'wacana'])
            ->with('success', "Teks Wacana '{$validated['title']}' berhasil ditambahkan.");
    }

    /**
     * Update an existing wacana text script.
     */
    public function updateWacana(Request $request, WacanaScript $script): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'script_text' => ['required', 'string'],
            'paragraph_count' => ['required', 'integer', 'min:1'],
        ]);

        $script->update($validated);

        return redirect()->route('admin.english.index', ['tab' => 'wacana'])
            ->with('success', 'Teks Wacana berhasil diperbarui.');
    }

    /**
     * Delete a wacana text script.
     */
    public function destroyWacana(WacanaScript $script): RedirectResponse
    {
        $script->delete();

        return redirect()->route('admin.english.index', ['tab' => 'wacana'])
            ->with('success', 'Teks Wacana berhasil dihapus.');
    }
}
