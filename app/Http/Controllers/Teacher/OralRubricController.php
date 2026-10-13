<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SpeakingBooking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OralRubricController extends Controller
{
    /**
     * Display live mobile-friendly digital grading rubric screen.
     */
    public function edit(Request $request, int $bookingId): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $booking = SpeakingBooking::where('id', $bookingId)
            ->where('mentor_id', $teacher->id)
            ->with(['student.user', 'student.academicClass', 'student.major', 'speakingTask'])
            ->firstOrFail();

        return view('guru.bimbingan.oral-rubric', compact('booking', 'teacher'));
    }

    /**
     * Submit rubric evaluation and compute aggregate score.
     */
    public function store(Request $request, int $bookingId): RedirectResponse
    {
        $validated = $request->validate([
            'rubric_pronunciation' => ['required', 'integer', 'min:1', 'max:25'],
            'rubric_fluency' => ['required', 'integer', 'min:1', 'max:25'],
            'rubric_vocabulary' => ['required', 'integer', 'min:1', 'max:25'],
            'rubric_comprehension' => ['required', 'integer', 'min:1', 'max:25'],
            'rubric_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $booking = SpeakingBooking::where('id', $bookingId)
            ->where('mentor_id', $teacher->id)
            ->firstOrFail();

        $totalGrade = (float) (
            $validated['rubric_pronunciation'] +
            $validated['rubric_fluency'] +
            $validated['rubric_vocabulary'] +
            $validated['rubric_comprehension']
        );

        $booking->update([
            'rubric_pronunciation' => $validated['rubric_pronunciation'],
            'rubric_fluency' => $validated['rubric_fluency'],
            'rubric_vocabulary' => $validated['rubric_vocabulary'],
            'rubric_comprehension' => $validated['rubric_comprehension'],
            'rubric_notes' => $validated['rubric_notes'],
            'grade' => $totalGrade,
            'status' => 'completed',
            'graded_at' => now(),
        ]);

        return redirect()->route('guru.bimbingan.student.profile', $booking->student_id)
            ->with('success', "Penilaian lisan untuk {$booking->student->user->name} berhasil disimpan dengan skor {$totalGrade}/100.");
    }
}
