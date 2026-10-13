<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\AttendanceCalculatorService;
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
}
