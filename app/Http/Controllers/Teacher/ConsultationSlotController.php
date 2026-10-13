<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ConsultationSlot;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationSlotController extends Controller
{
    /**
     * Display the mentor's consultation / tentoring slots.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $slots = ConsultationSlot::where('mentor_id', $teacher->id)
            ->with(['bookings.student.user', 'bookings.student.academicClass'])
            ->orderBy('date_time', 'desc')
            ->paginate(15);

        return view('guru.konsultasi.index', compact('slots', 'teacher'));
    }

    /**
     * Store a newly created consultation slot.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'topic' => ['required', 'string', 'max:150'],
            'date_time' => ['required', 'date', 'after:now'],
            'max_quota' => ['required', 'integer', 'min:1', 'max:20'],
            'location_or_link' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        ConsultationSlot::create([
            'mentor_id' => $teacher->id,
            'topic' => $validated['topic'],
            'date_time' => $validated['date_time'],
            'max_quota' => $validated['max_quota'],
            'remaining_quota' => $validated['max_quota'],
            'location_or_link' => $validated['location_or_link'] ?? 'Ruang Konsultasi Alto',
        ]);

        return redirect()->route('guru.konsultasi.index')
            ->with('success', 'Jadwal bimbingan tentoring berhasil dibuka.');
    }

    /**
     * Delete a consultation slot.
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher;

        $slot = ConsultationSlot::where('id', $id)
            ->where('mentor_id', $teacher->id)
            ->firstOrFail();

        $slot->delete();

        return redirect()->route('guru.konsultasi.index')
            ->with('success', 'Jadwal bimbingan berhasil dihapus.');
    }
}
