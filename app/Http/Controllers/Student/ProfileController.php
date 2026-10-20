<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\AttendanceCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the integrated student academic profile.
     */
    public function index(Request $request, AttendanceCalculatorService $calculator): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        $student->loadMissing(['user', 'academicClass', 'major', 'mentor.user', 'counselor']);

        $attendanceStats = $calculator->calculate($student->id);

        return view('siswa.profil.index', compact('user', 'student', 'attendanceStats'));
    }

    /**
     * Complete student NISN (Rule 1: Profile Completion).
     */
    public function updateNisn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => ['required', 'string', 'max:30', 'unique:students,nisn,'.$request->user()->student?->id],
        ]);

        /** @var User $user */
        $user = $request->user();
        $student = $user->student;

        if ($student) {
            $student->update(['nisn' => trim($validated['nisn'])]);
        }

        return back()->with('success', 'Profil akademik berhasil dilengkapi dengan NISN Anda.');
    }
}
