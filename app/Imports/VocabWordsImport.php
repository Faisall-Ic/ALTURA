<?php

namespace App\Imports;

use App\Models\VocabWord;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VocabWordsImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public function __construct(public ?int $defaultModuleNumber = null) {}

    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row): ?VocabWord
    {
        $englishWord = trim((string) ($row['english_word'] ?? $row['word'] ?? $row['kata_inggris'] ?? ''));
        $indonesianMeaning = trim((string) ($row['indonesian_meaning'] ?? $row['meaning'] ?? $row['arti'] ?? $row['terjemahan'] ?? ''));

        if (empty($englishWord) || empty($indonesianMeaning)) {
            return null;
        }

        $moduleNumber = (int) ($row['module_number'] ?? $row['modul'] ?? $this->defaultModuleNumber ?? 1);

        // Process acceptable synonyms JSON
        $rawAcceptable = $row['acceptable_answers'] ?? $row['sinonim'] ?? $row['jawaban_diterima'] ?? '';
        $acceptableAnswers = [];

        if (is_array($rawAcceptable)) {
            $acceptableAnswers = $rawAcceptable;
        } elseif (is_string($rawAcceptable) && ! empty($rawAcceptable)) {
            $trimmed = trim($rawAcceptable);
            if (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    $acceptableAnswers = $decoded;
                }
            } else {
                // Comma or semicolon separated string: e.g. "cepat, lekas, kilat"
                $parts = preg_split('/[,;]+/', $trimmed);
                $acceptableAnswers = array_values(array_filter(array_map('trim', $parts)));
            }
        }

        $exampleSentence = trim((string) ($row['example_sentence'] ?? $row['contoh_kalimat'] ?? $row['contoh'] ?? ''));

        return VocabWord::updateOrCreate(
            [
                'module_number' => $moduleNumber,
                'english_word' => $englishWord,
            ],
            [
                'indonesian_meaning' => $indonesianMeaning,
                'acceptable_answers' => $acceptableAnswers ?: null,
                'example_sentence' => $exampleSentence ?: null,
            ]
        );
    }
}
