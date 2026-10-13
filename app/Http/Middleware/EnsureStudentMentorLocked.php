<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentMentorLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user && $user->hasRole('siswa')) {
            $student = $user->student;

            if (! $student) {
                abort(403, 'Profil siswa tidak ditemukan.');
            }

            // Check mentor_id from route parameter or request payload
            $targetMentorId = $request->route('mentorId')
                ?? $request->route('teacherId')
                ?? $request->input('mentor_id')
                ?? $request->input('teacher_id');

            if ($targetMentorId !== null && (int) $targetMentorId !== (int) $student->mentor_id) {
                abort(403, 'Akses ditolak: Anda hanya dapat melakukan setoran dan bimbingan dengan Mentor resmi Anda yang telah ditetapkan.');
            }
        }

        return $next($request);
    }
}
