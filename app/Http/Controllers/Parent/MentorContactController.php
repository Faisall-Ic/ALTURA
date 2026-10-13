<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorContactController extends Controller
{
    /**
     * Display direct WhatsApp communication bridge to Mentor & Counselor.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $child = $user->children()->with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])->first()
            ?? Student::with(['user', 'academicClass', 'major', 'mentor.user', 'counselor'])->first();

        $counselor = $child?->counselor ?? User::role('guru_bk')->first();
        $mentor = $child?->mentor;

        return view('orang_tua.kontak-pembimbing', compact('user', 'child', 'mentor', 'counselor'));
    }
}
