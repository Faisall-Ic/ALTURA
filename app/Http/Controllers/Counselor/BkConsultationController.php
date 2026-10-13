<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use App\Models\ConsultationSlot;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BkConsultationController extends Controller
{
    /**
     * Display BK counseling sessions for students and parents.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = ConsultationBooking::with(['slot.mentor.user', 'student.user', 'student.academicClass', 'parent'])
            ->where('type', 'parent_bk')
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(12)->withQueryString();

        $slots = ConsultationSlot::with(['mentor.user', 'bookings'])
            ->where('date_time', '>=', now()->subDays(1))
            ->orderBy('date_time', 'asc')
            ->get();

        $teachers = Teacher::with('user')->get();

        return view('guru_bk.konsultasi.index', compact('bookings', 'slots', 'teachers', 'status'));
    }

    /**
     * Create a new BK counseling slot.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mentor_id' => ['required', 'exists:teachers,id'],
            'topic' => ['required', 'string', 'max:150'],
            'date_time' => ['required', 'date', 'after:now'],
            'max_quota' => ['required', 'integer', 'min:1', 'max:10'],
            'location_or_link' => ['required', 'string', 'max:255'],
        ]);

        ConsultationSlot::create([
            'mentor_id' => $validated['mentor_id'],
            'topic' => $validated['topic'],
            'date_time' => $validated['date_time'],
            'max_quota' => $validated['max_quota'],
            'remaining_quota' => $validated['max_quota'],
            'location_or_link' => $validated['location_or_link'],
        ]);

        return redirect()->route('bk.konsultasi.sessions')->with('success', 'Jadwal sesi konsultasi BK berhasil dibuka.');
    }

    /**
     * Update booking status and counseling notes.
     */
    public function updateStatus(Request $request, int|string $id): RedirectResponse
    {
        $booking = ConsultationBooking::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,attended,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $booking->notes,
        ]);

        return redirect()->back()->with('success', 'Status sesi konsultasi berhasil diperbarui.');
    }
}
