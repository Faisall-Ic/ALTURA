<?php

namespace App\Imports;

use App\Models\Tryout;
use App\Models\TryoutQuestion;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TryoutQuestionsImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public function __construct(public ?int $tryoutId = null) {}

    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row): ?TryoutQuestion
    {
        $targetTryoutId = $this->tryoutId ?: ($row['tryout_id'] ?? Tryout::first()?->id);
        if (! $targetTryoutId) {
            return null;
        }

        $subtestCode = strtoupper(trim((string) ($row['subtest_code'] ?? $row['subtes'] ?? 'TPS')));
        $questionText = trim((string) ($row['question_text'] ?? $row['soal'] ?? $row['pertanyaan'] ?? ''));

        if (empty($questionText)) {
            return null;
        }

        // Build options JSON
        $options = [];
        if (! empty($row['options']) && is_string($row['options']) && str_starts_with(trim($row['options']), '{')) {
            $options = json_decode($row['options'], true) ?: [];
        } else {
            $options = [
                'A' => trim((string) ($row['option_a'] ?? $row['pilihan_a'] ?? $row['a'] ?? '')),
                'B' => trim((string) ($row['option_b'] ?? $row['pilihan_b'] ?? $row['b'] ?? '')),
                'C' => trim((string) ($row['option_c'] ?? $row['pilihan_c'] ?? $row['c'] ?? '')),
                'D' => trim((string) ($row['option_d'] ?? $row['pilihan_d'] ?? $row['d'] ?? '')),
                'E' => trim((string) ($row['option_e'] ?? $row['pilihan_e'] ?? $row['e'] ?? '')),
            ];
        }

        $correctAnswer = strtoupper(trim((string) ($row['correct_answer'] ?? $row['kunci'] ?? $row['kunci_jawaban'] ?? 'A')));
        $explanation = trim((string) ($row['explanation'] ?? $row['pembahasan'] ?? ''));
        $imagePath = trim((string) ($row['image_path'] ?? $row['gambar'] ?? ''));

        return new TryoutQuestion([
            'tryout_id' => $targetTryoutId,
            'subtest_code' => $subtestCode,
            'question_text' => $questionText,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'explanation' => $explanation ?: null,
            'image_path' => $imagePath ?: null,
        ]);
    }
}
