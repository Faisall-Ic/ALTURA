<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\TryoutScore;
use App\Services\PdfReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TryoutReportController extends Controller
{
    /**
     * Generate & display/stream printable official Tryout PDF.
     */
    public function downloadPdf(Request $request, int|string $tryoutScoreId, PdfReportService $pdfService): View
    {
        $score = TryoutScore::with(['student.user', 'student.academicClass', 'student.major', 'student.mentor.user', 'tryout'])
            ->findOrFail($tryoutScoreId);

        return $pdfService->renderTryoutReport($score);
    }
}
