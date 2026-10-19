<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CounselingBooking;
use App\Models\CounselingSlot;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselingSlotController extends Controller
{
    /**
     * Display a listing of BK counseling slots and bookings.
     */
    public function index(Request $request): View
    {
        $counselorId = $request->query('counselor_id');

        $slots = CounselingSlot::with(['counselor', 'bookings.student.user', 'bookings.parent.user'])
            ->withCount('bookings')
            ->when($counselorId, fn ($q) => $q->where('counselor_user_id', $counselorId))
            ->latest('date_time')
            ->paginate(12)
            ->withQueryString();

        $counselors = User::role('guru_bk')->orderBy('name')->get();

        return view('admin.konsultasi.bk.index', compact('slots', 'counselors', 'counselorId'));
    }

    /**
     * Store a newly created BK counseling slot.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'counselor_user_id' => ['required', 'exists:users,id'],
            'date_time' => ['required', 'date'],
            'room_or_link' => ['required', 'string', 'max:255'],
            'max_quota' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $validated['remaining_quota'] = $validated['max_quota'];

        CounselingSlot::create($validated);

        return redirect()->route('admin.konseling-bk.index')
            ->with('success', 'Slot sesi bimbingan konseling (BK) berhasil dibuka.');
    }

    /**
     * Delete a BK counseling slot.
     */
    public function destroy(CounselingSlot $slot): RedirectResponse
    {
        $slot->bookings()->delete();
        $slot->delete();

        return redirect()->route('admin.konseling-bk.index')
            ->with('success', 'Slot sesi bimbingan konseling (BK) berhasil dihapus.');
    }

    /**
     * Update counseling booking status.
     */
    public function updateBookingStatus(Request $request, CounselingBooking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:booked,approved,completed,cancelled'],
        ]);

        $booking->update($validated);

        return back()->with('success', "Status reservasi konseling diubah menjadi '{$booking->status}'.");
    }
}
