<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\PdfReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AcademicProgressReportController extends Controller
{
    /**
     * Generate & display/stream student academic progress report PDF.
     */
    public function downloadPdf(Request $request, int|string $studentId, PdfReportService $pdfService): View
    {
        $student = Student::with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])
            ->findOrFail($studentId);

        return $pdfService->renderAcademicProgressReport($student);
    }
}
