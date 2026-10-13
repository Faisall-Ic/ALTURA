<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SpeakingBooking;
use App\Models\SpeakingTask;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpeakingBookingController extends Controller
{
    /**
     * Display oral examination booking interface locked strictly to the assigned mentor.
     */
    public function create(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;
        $mentor = $student->mentor;

        $tasks = SpeakingTask::orderBy('type')->orderBy('title')->get();

        $myBookings = SpeakingBooking::where('student_id', $student->id)
            ->with(['speakingTask', 'mentor.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.english.booking-oral', compact('student', 'mentor', 'tasks', 'myBookings'));
    }

    /**
     * Store oral examination booking strictly with assigned mentor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'speaking_task_id' => ['required', 'exists:speaking_tasks,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'string', 'max:30'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        // Check if student already has a pending booking for this task
        $hasPending = SpeakingBooking::where('student_id', $student->id)
            ->where('speaking_task_id', $validated['speaking_task_id'])
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Anda sudah memiliki jadwal setoran lisan yang menunggu evaluasi untuk naskah ini.');
        }

        SpeakingBooking::create([
            'student_id' => $student->id,
            'mentor_id' => $student->mentor_id,
            'speaking_task_id' => $validated['speaking_task_id'],
            'booking_date' => $validated['booking_date'],
            'time_slot' => $validated['time_slot'],
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.english.speaking.booking')
            ->with('success', 'Jadwal setoran lisan berhasil didaftarkan ke Guru Mentor Anda.');
    }
}
