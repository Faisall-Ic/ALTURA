<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VocabQuizResult;
use App\Models\VocabWord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VocabQuizController extends Controller
{
    /**
     * Display the 8 modular English vocabulary modules overview.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        // Group quiz results by module
        $results = VocabQuizResult::where('student_id', $student->id)
            ->orderBy('score', 'desc')
            ->get()
            ->groupBy('module_number');

        $modules = [];
        for ($i = 1; $i <= 8; $i++) {
            $moduleResults = $results->get($i);
            $bestResult = $moduleResults?->first();

            $modules[] = [
                'number' => $i,
                'word_count' => VocabWord::byModule($i)->count(),
                'best_score' => $bestResult?->score,
                'passed' => $bestResult?->passed ?? false,
                'attempts' => $moduleResults ? $moduleResults->count() : 0,
            ];
        }

        return view('siswa.english.vocab-quiz', compact('student', 'modules'));
    }

    /**
     * Take a 10-question text input (essay) quiz for a specific module.
     */
    public function take(Request $request, int $module): View|RedirectResponse
    {
        if ($module < 1 || $module > 8) {
            return redirect()->route('siswa.english.vocab.index')->with('error', 'Modul tidak valid.');
        }

        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        // Pick 10 random words from this module
        $words = VocabWord::byModule($module)->inRandomOrder()->limit(10)->get();

        if ($words->isEmpty()) {
            return redirect()->route('siswa.english.vocab.index')->with('error', 'Belum ada bank kosakata untuk modul ini.');
        }

        return view('siswa.english.vocab-essay', compact('student', 'module', 'words'));
    }

    /**
     * Submit and auto-grade the typed text input (essay) quiz.
     */
    public function submit(Request $request, int $module): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $answers = $request->input('answers', []);
        if (empty($answers) || ! is_array($answers)) {
            return back()->with('error', 'Harap jawab pertanyaan kuis sebelum mengirimkan.');
        }

        $wordIds = array_keys($answers);
        $words = VocabWord::whereIn('id', $wordIds)->get()->keyBy('id');

        $correctCount = 0;
        $totalCount = count($answers);

        foreach ($answers as $wordId => $studentInput) {
            $word = $words->get($wordId);
            if (! $word) {
                continue;
            }

            // Convert typed input to lowercase & trim whitespace
            $typed = trim(strtolower((string) $studentInput));

            // Parse acceptable_answers JSON array from vocab_words table
            $acceptableAnswers = $word->acceptable_answers;
            if (is_string($acceptableAnswers)) {
                $decoded = json_decode($acceptableAnswers, true);
                $acceptableAnswers = is_array($decoded) ? $decoded : [];
            } elseif (! is_array($acceptableAnswers)) {
                $acceptableAnswers = [];
            }

            $acceptableList = array_map(fn ($val) => trim(strtolower((string) $val)), $acceptableAnswers);

            $targetEnglish = trim(strtolower((string) $word->english_word));
            $targetIndo = trim(strtolower((string) $word->indonesian_meaning));

            // If $typed matches ANY item in acceptable_answers OR matches english_word / indonesian_meaning, mark as CORRECT
            if ($typed !== '' && ($typed === $targetEnglish || $typed === $targetIndo || in_array($typed, $acceptableList, true))) {
                $correctCount++;
            }
        }

        $score = $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 2) : 0;
        $passed = $score >= 75.00;

        VocabQuizResult::create([
            'student_id' => $student->id,
            'module_number' => $module,
            'score' => $score,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        $message = $passed
            ? "Selamat! Anda lulus Kuis Modul {$module} dengan skor {$score}% ({$correctCount}/{$totalCount} benar)."
            : "Kuis selesai. Skor Anda {$score}% ({$correctCount}/{$totalCount} benar). Batas kelulusan adalah 75%. Silakan pelajari kembali dan coba lagi.";

        return redirect()->route('siswa.english.vocab.index')
            ->with($passed ? 'success' : 'error', $message);
    }
}
