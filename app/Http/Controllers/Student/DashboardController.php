<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $student = $user->student()->with(['major', 'academicClass', 'mentor.user', 'counselor'])->first();

        return view('siswa.dashboard', [
            'user' => $user,
            'student' => $student,
        ]);
    }
}
