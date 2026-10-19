<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use App\Models\TryoutAttempt;
use App\Models\TryoutQuestion;
use App\Models\TryoutScore;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentTryoutExamController extends Controller
{
    /**
     * Subtest display names mapping.
     */
    public const SUBTEST_NAMES = [
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
     * Take live CBT online examination.
     */
    public function take(Request $request, int|string $id): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        if (! $student) {
            abort(403, 'Akses khusus profil siswa.');
        }

        $tryout = Tryout::with('questions')->findOrFail($id);

        // Record or resume attempt
        $attempt = TryoutAttempt::firstOrCreate(
            [
                'student_id' => $student->id,
                'tryout_id' => $tryout->id,
            ],
            [
                'started_at' => now(),
                'status' => 'in_progress',
            ]
        );

        // If tryout has no questions yet, show warning
        if ($tryout->questions->isEmpty()) {
            return redirect()->route('siswa.tryout.index')
                ->with('error', 'Paket tryout ini belum memiliki butir soal yang diunggah. Silakan hubungi admin.');
        }

        $questionsData = $tryout->questions->map(function (TryoutQuestion $q) {
            $rawOptions = is_array($q->options) ? $q->options : (json_decode($q->options, true) ?? []);

            $normalizedOptions = [];
            $alphabet = ['A', 'B', 'C', 'D', 'E'];
            foreach ($alphabet as $index => $letter) {
                if (isset($rawOptions[$letter])) {
                    $normalizedOptions[$letter] = $rawOptions[$letter];
                } elseif (isset($rawOptions[$index])) {
                    $normalizedOptions[$letter] = $rawOptions[$index];
                }
            }
            if (empty($normalizedOptions)) {
                $normalizedOptions = $rawOptions;
            }

            return [
                'id' => $q->id,
                'subtest_code' => $q->subtest_code,
                'subtest_name' => self::SUBTEST_NAMES[$q->subtest_code] ?? $q->subtest_code,
                'question_text' => $q->question_text,
                'image_path' => $q->image_path,
                'options' => $normalizedOptions,
            ];
        })->values();

        return view('siswa.tryout.take', compact('student', 'tryout', 'attempt', 'questionsData'));
    }

    /**
     * Submit CBT answers, grade attempt, and save final TryoutScore record.
     */
    public function submit(Request $request, int|string $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tryout = Tryout::with('questions')->findOrFail($id);
        $studentAnswers = $request->input('answers', []);

        $attempt = TryoutAttempt::where('student_id', $student->id)
            ->where('tryout_id', $tryout->id)
            ->first();

        if (! $attempt) {
            $attempt = TryoutAttempt::create([
                'student_id' => $student->id,
                'tryout_id' => $tryout->id,
                'started_at' => now()->subMinutes(60),
                'status' => 'in_progress',
            ]);
        }

        $attempt->update([
            'submitted_at' => now(),
            'status' => 'completed',
        ]);

        // Evaluate answers per subtest
        $subtestStats = [];
        $totalQuestions = $tryout->questions->count();
        $totalCorrect = 0;

        foreach ($tryout->questions as $question) {
            $subtest = $question->subtest_code ?: 'TPS';
            if (! isset($subtestStats[$subtest])) {
                $subtestStats[$subtest] = ['total' => 0, 'correct' => 0];
            }
            $subtestStats[$subtest]['total']++;

            $givenAnswer = strtoupper(trim((string) ($studentAnswers[$question->id] ?? '')));
            $correctAnswer = strtoupper(trim((string) $question->correct_answer));
            $isCorrect = ($givenAnswer !== '' && $givenAnswer === $correctAnswer);

            if ($isCorrect) {
                $subtestStats[$subtest]['correct']++;
                $totalCorrect++;
            }

            TryoutAnswer::updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'student_answer' => $givenAnswer ?: null,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        // Calculate scaled subtest scores (0 - 1000 scale)
        // Formula: baseline 250 + percentage * 750
        $subtestScores = [];
        foreach ($subtestStats as $code => $stat) {
            $pct = $stat['total'] > 0 ? ($stat['correct'] / $stat['total']) : 0;
            $scaled = round(250 + ($pct * 750), 2);
            $subtestScores[$code] = $scaled;
        }

        $avgScore = ! empty($subtestScores) ? round(array_sum($subtestScores) / count($subtestScores), 2) : 500;

        // Legacy subtest fields for reports and legacy views
        $tpsScore = $subtestScores['TPS'] ?? $subtestScores['PU'] ?? $avgScore;
        $indoScore = $subtestScores['LBI'] ?? $subtestScores['PBM'] ?? $avgScore;
        $inggrisScore = $subtestScores['LBS'] ?? $avgScore;
        $matematikaScore = $subtestScores['PM'] ?? $subtestScores['PK'] ?? $avgScore;

        TryoutScore::updateOrCreate(
            [
                'student_id' => $student->id,
                'tryout_id' => $tryout->id,
            ],
            [
                'subtest_scores' => $subtestScores,
                'subtest_tps' => $tpsScore,
                'subtest_indo' => $indoScore,
                'subtest_inggris' => $inggrisScore,
                'subtest_matematika' => $matematikaScore,
                'average_score' => $avgScore,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Ujian Tryout berhasil diselesaikan dan dinilai otomatis.',
            'redirect' => route('siswa.tryout.show', $tryout->id),
        ]);
    }
}
