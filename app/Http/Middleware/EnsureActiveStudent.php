<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveStudent
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

            if (! $student || ! $student->status_active) {
                abort(403, 'Akses ditolak: Akun siswa Anda saat ini berstatus non-aktif. Silakan hubungi bagian tata usaha Alto.');
            }
        }

        return $next($request);
    }
}
