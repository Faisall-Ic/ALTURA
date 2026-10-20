<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\TryoutQuestionsImport;
use App\Models\Tryout;
use App\Models\TryoutQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AdminTryoutQuestionController extends Controller
{
    /**
     * Subtest code to display name mapping.
     */
    public const SUBTESTS = [
        'TPS' => 'Tes Potensi Skolastik (TPS)',
        'PU' => 'Penalaran Umum (PU)',
        'PK' => 'Pengetahuan Kuantitatif (PK)',
        'PBM' => 'Pemahaman Bacaan & Menulis (PBM)',
        'PPU' => 'Pengetahuan & Pemahaman Umum (PPU)',
        'LBI' => 'Literasi Bahasa Indonesia (LBI)',
        'LBS' => 'Literasi Bahasa Inggris (LBS)',
        'PM' => 'Penalaran Matematika (PM)',
    ];

    /**
     * Display a listing of questions for a specific tryout package.
     */
    public function index(Request $request, int|string $id): View
    {
        $tryout = Tryout::withCount('questions')->findOrFail($id);

        $selectedSubtest = $request->query('subtest');

        $questionsQuery = TryoutQuestion::where('tryout_id', $tryout->id);
        if ($selectedSubtest && array_key_exists($selectedSubtest, self::SUBTESTS)) {
            $questionsQuery->where('subtest_code', $selectedSubtest);
        }

        $questions = $questionsQuery->latest()->paginate(15)->withQueryString();

        // Calculate count per subtest for quick overview badges
        $subtestCounts = TryoutQuestion::where('tryout_id', $tryout->id)
            ->selectRaw('subtest_code, count(*) as count')
            ->groupBy('subtest_code')
            ->pluck('count', 'subtest_code')
            ->toArray();

        $subtests = self::SUBTESTS;

        return view('admin.tryout.questions.index', compact('tryout', 'questions', 'subtests', 'subtestCounts', 'selectedSubtest'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request, int|string $id): RedirectResponse
    {
        $tryout = Tryout::findOrFail($id);

        $validated = $request->validate([
            'subtest_code' => ['required', 'string', 'in:'.implode(',', array_keys(self::SUBTESTS))],
            'question_text' => ['required', 'string'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'option_e' => ['required', 'string'],
            'correct_answer' => ['required', 'string', 'in:A,B,C,D,E'],
            'explanation' => ['nullable', 'string'],
        ]);

        $options = [
            'A' => $validated['option_a'],
            'B' => $validated['option_b'],
            'C' => $validated['option_c'],
            'D' => $validated['option_d'],
            'E' => $validated['option_e'],
        ];

        TryoutQuestion::create([
            'tryout_id' => $tryout->id,
            'subtest_code' => $validated['subtest_code'],
            'question_text' => $validated['question_text'],
            'image_path' => $validated['image_path'] ?? null,
            'options' => $options,
            'correct_answer' => strtoupper($validated['correct_answer']),
            'explanation' => $validated['explanation'] ?? null,
        ]);

        return redirect()->route('admin.tryouts.questions.index', $tryout->id)
            ->with('success', 'Butir soal baru berhasil ditambahkan.');
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, int|string $id, TryoutQuestion $question): RedirectResponse
    {
        $tryout = Tryout::findOrFail($id);

        if ($question->tryout_id !== $tryout->id) {
            abort(404);
        }

        $validated = $request->validate([
            'subtest_code' => ['required', 'string', 'in:'.implode(',', array_keys(self::SUBTESTS))],
            'question_text' => ['required', 'string'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'option_e' => ['required', 'string'],
            'correct_answer' => ['required', 'string', 'in:A,B,C,D,E'],
            'explanation' => ['nullable', 'string'],
        ]);

        $options = [
            'A' => $validated['option_a'],
            'B' => $validated['option_b'],
            'C' => $validated['option_c'],
            'D' => $validated['option_d'],
            'E' => $validated['option_e'],
        ];

        $question->update([
            'subtest_code' => $validated['subtest_code'],
            'question_text' => $validated['question_text'],
            'image_path' => $validated['image_path'] ?? null,
            'options' => $options,
            'correct_answer' => strtoupper($validated['correct_answer']),
            'explanation' => $validated['explanation'] ?? null,
        ]);

        return redirect()->route('admin.tryouts.questions.index', $tryout->id)
            ->with('success', 'Butir soal berhasil diperbarui.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(int|string $id, TryoutQuestion $question): RedirectResponse
    {
        $tryout = Tryout::findOrFail($id);

        if ($question->tryout_id !== $tryout->id) {
            abort(404);
        }

        $question->answers()->delete();
        $question->delete();

        return redirect()->route('admin.tryouts.questions.index', $tryout->id)
            ->with('success', 'Butir soal berhasil dihapus.');
    }

    /**
     * Bulk import questions via Excel spreadsheet.
     */
    public function importExcel(Request $request, int|string $id): RedirectResponse
    {
        $tryout = Tryout::findOrFail($id);

        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        try {
            Excel::import(new TryoutQuestionsImport($tryout->id), $request->file('excel_file'));

            return redirect()->route('admin.tryouts.questions.index', $tryout->id)
                ->with('success', 'Bank soal Tryout berhasil diimpor secara massal dari Excel.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.tryouts.questions.index', $tryout->id)
                ->with('error', 'Gagal mengimpor file Excel: '.$e->getMessage());
        }
    }
}
