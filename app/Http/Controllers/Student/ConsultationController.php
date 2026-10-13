<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use App\Models\ConsultationSlot;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    /**
     * Display available tentoring slots and student bookings.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        // Open slots
        $openSlots = ConsultationSlot::with('mentor.user')
            ->where('date_time', '>=', now())
            ->where('remaining_quota', '>', 0)
            ->orderBy('date_time', 'asc')
            ->get();

        // Student's active bookings
        $myBookings = ConsultationBooking::with(['slot.mentor.user'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.konsultasi.index', compact('student', 'openSlots', 'myBookings'));
    }

    /**
     * Book an open tentoring slot.
     */
    public function book(Request $request, int $slotId): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $slot = ConsultationSlot::findOrFail($slotId);

        // Check if student already booked this slot
        $alreadyBooked = ConsultationBooking::where('slot_id', $slot->id)
            ->where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Anda sudah memesan slot konsultasi ini sebelumnya.');
        }

        if ($slot->remaining_quota <= 0) {
            return back()->with('error', 'Mohon maaf, kuota slot ini telah habis.');
        }

        DB::transaction(function () use ($slot, $student, $request) {
            $slot->decrement('remaining_quota');

            ConsultationBooking::create([
                'slot_id' => $slot->id,
                'student_id' => $student->id,
                'type' => 'student_mapel',
                'status' => 'confirmed',
                'notes' => $request->input('notes'),
            ]);
        });

        return redirect()->route('siswa.konsultasi.index')
            ->with('success', 'Slot bimbingan berhasil dipesan. Silakan hadir tepat waktu.');
    }

    /**
     * Cancel a booked consultation slot.
     */
    public function cancel(Request $request, int $bookingId): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $booking = ConsultationBooking::where('id', $bookingId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($booking->status === 'confirmed') {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'cancelled']);
                $booking->slot()->increment('remaining_quota');
            });
        }

        return redirect()->route('siswa.konsultasi.index')
            ->with('success', 'Pemesanan konsultasi berhasil dibatalkan.');
    }
}
