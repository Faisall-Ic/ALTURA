<?php

namespace App\Imports;

use App\Models\WacanaText;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WacanaTextsImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row): ?WacanaText
    {
        $title = trim((string) ($row['title'] ?? $row['judul'] ?? ''));
        $content = trim((string) ($row['content'] ?? $row['isi'] ?? $row['teks'] ?? $row['naskah'] ?? ''));

        if (empty($title) || empty($content)) {
            return null;
        }

        // Calculate paragraph count if not provided
        $paragraphCount = ! empty($row['paragraph_count'] ?? $row['jumlah_paragraf'] ?? null)
            ? (int) ($row['paragraph_count'] ?? $row['jumlah_paragraf'])
            : max(1, count(preg_split('/\n\s*\n/', trim($content))));

        return WacanaText::updateOrCreate(
            ['title' => $title],
            [
                'content' => $content,
                'paragraph_count' => $paragraphCount,
            ]
        );
    }
}
