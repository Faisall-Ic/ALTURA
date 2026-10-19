<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectConsultationBooking;
use App\Models\SubjectConsultationSlot;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectConsultationController extends Controller
{
    /**
     * Display a listing of subject consultation (tentoring mapel) slots and bookings.
     */
    public function index(Request $request): View
    {
        $subjectId = $request->query('subject_id');
        $teacherId = $request->query('teacher_id');

        $slots = SubjectConsultationSlot::with(['subject', 'teacher.user', 'bookings.student.user'])
            ->withCount('bookings')
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($teacherId, fn ($q) => $q->where('teacher_id', $teacherId))
            ->latest('date_time')
            ->paginate(12)
            ->withQueryString();

        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.konsultasi.mapel.index', compact('slots', 'subjects', 'teachers', 'subjectId', 'teacherId'));
    }

    /**
     * Store a newly created subject consultation slot.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'topic' => ['required', 'string', 'max:200'],
            'date_time' => ['required', 'date'],
            'room_or_link' => ['required', 'string', 'max:255'],
            'max_quota' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $validated['remaining_quota'] = $validated['max_quota'];

        SubjectConsultationSlot::create($validated);

        return redirect()->route('admin.konsultasi-mapel.index')
            ->with('success', 'Slot tentoring mata pelajaran berhasil dibuka.');
    }

    /**
     * Delete a subject consultation slot.
     */
    public function destroy(SubjectConsultationSlot $slot): RedirectResponse
    {
        $slot->bookings()->delete();
        $slot->delete();

        return redirect()->route('admin.konsultasi-mapel.index')
            ->with('success', 'Slot tentoring mata pelajaran berhasil dihapus.');
    }

    /**
     * Update booking status.
     */
    public function updateBookingStatus(Request $request, SubjectConsultationBooking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:booked,approved,completed,cancelled'],
        ]);

        $booking->update($validated);

        return back()->with('success', "Status reservasi tentoring siswa diubah menjadi '{$booking->status}'.");
    }
}
