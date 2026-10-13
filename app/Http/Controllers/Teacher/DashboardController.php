<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the teacher/mentor dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $teacher = $user->teacher()->with(['mentees.user', 'mentees.academicClass'])->first();

        return view('guru.dashboard', [
            'user' => $user,
            'teacher' => $teacher,
        ]);
    }
}
