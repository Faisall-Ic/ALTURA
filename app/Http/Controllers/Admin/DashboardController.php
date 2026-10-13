<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin central dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => AcademicClass::count(),
            'total_majors' => Major::count(),
        ];

        return view('admin.dashboard', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }
}
