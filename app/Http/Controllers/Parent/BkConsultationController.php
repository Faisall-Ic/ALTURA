<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use App\Models\ConsultationSlot;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BkConsultationController extends Controller
{
    /**
     * Display booking interface for counseling consultation with Guru BK.
     */
    public function create(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $children = $user->children()->with(['counselor', 'mentor.user'])->get();
        if ($children->isEmpty()) {
            $children = Student::with(['counselor', 'mentor.user'])->take(1)->get();
        }

        // Available slots
        $availableSlots = ConsultationSlot::with('mentor.user')
            ->where('date_time', '>=', now())
            ->where('remaining_quota', '>', 0)
            ->orderBy('date_time', 'asc')
            ->get();

        // Previous bookings
        $myBookings = ConsultationBooking::with(['slot.mentor.user', 'student.user'])
            ->where('parent_id', $user->id)
            ->where('type', 'parent_bk')
            ->latest()
            ->get();

        return view('orang_tua.konsultasi.booking-bk', compact('user', 'children', 'availableSlots', 'myBookings'));
    }

    /**
     * Store new counseling booking.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'slot_id' => ['required', 'exists:consultation_slots,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'notes' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $slot = ConsultationSlot::lockForUpdate()->findOrFail($validated['slot_id']);

        if ($slot->remaining_quota <= 0) {
            return redirect()->back()->with('error', 'Mohon maaf, kuota sesi konsultasi ini sudah habis.');
        }

        $studentId = $validated['student_id'] ?? $user->children()->first()?->id ?? Student::first()?->id;

        // Check duplicate booking on the same slot
        $existing = ConsultationBooking::where('slot_id', $slot->id)
            ->where('parent_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda telah terdaftar pada sesi konsultasi ini.');
        }

        ConsultationBooking::create([
            'slot_id' => $slot->id,
            'parent_id' => $user->id,
            'student_id' => $studentId,
            'type' => 'parent_bk',
            'status' => 'confirmed',
            'notes' => $validated['notes'],
        ]);

        $slot->decrement('remaining_quota');

        return redirect()->route('ortu.konsultasi.booking')->with('success', 'Sesi konsultasi BK berhasil dijadwalkan! Guru BK kami akan menghubungi Anda.');
    }
}
