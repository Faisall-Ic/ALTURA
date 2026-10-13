<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoEnrollmentAccess
{
    /**
     * Handle an incoming request.
     *
     * Enforces Zero E-Commerce / Zero Paywall access based on registered Major and Class.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('siswa')) {
            $student = $user->student()->with('major')->first();

            if (! $student || ! $student->status_active) {
                abort(403, 'Akses ditolak: Status siswa tidak aktif.');
            }

            // Target major requirement check (if parameter exists on route/request)
            $targetMajorId = $request->route('majorId') ?? $request->input('major_id');

            if ($targetMajorId !== null && (int) $targetMajorId !== (int) $student->major_id) {
                // Check if target content is SNBT core and student has mandatory SNBT
                $isSnbtMandatory = $student->major?->is_snbt_mandatory ?? false;
                if (! $isSnbtMandatory) {
                    abort(403, 'Akses dibatasi: Konten pembelajaran ini khusus untuk jurusan terdaftar.');
                }
            }

            $request->attributes->set('enrolled_student', $student);
        }

        return $next($request);
    }
}
