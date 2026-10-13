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
     * Take a 10-question CBT quiz for a specific module.
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

        // Get pool of distractor meanings from other words
        $allMeanings = VocabWord::where('module_number', $module)->pluck('indonesian_meaning')->toArray();

        $quizQuestions = $words->map(function ($word) use ($allMeanings) {
            $correct = $word->indonesian_meaning;

            // Pick 3 distractors
            $distractors = collect($allMeanings)
                ->filter(fn ($m) => $m !== $correct)
                ->shuffle()
                ->take(3)
                ->values()
                ->toArray();

            $options = array_merge([$correct], $distractors);
            shuffle($options);

            return [
                'id' => $word->id,
                'english_word' => $word->english_word,
                'example_sentence' => $word->example_sentence,
                'options' => $options,
            ];
        });

        return view('siswa.english.quiz-take', compact('student', 'module', 'quizQuestions'));
    }

    /**
     * Submit and auto-grade the CBT quiz.
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

        foreach ($answers as $wordId => $selectedMeaning) {
            $word = $words->get($wordId);
            if ($word && trim($word->indonesian_meaning) === trim($selectedMeaning)) {
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
