<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Handle the incoming request and redirect based on the user's role.
     */
    public function handle(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        return self::roleDashboardRedirect($user);
    }

    /**
     * Get the redirect response for a user according to their primary role.
     */
    public static function roleDashboardRedirect(User $user): RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->hasRole('guru')) {
            return redirect()->intended(route('guru.dashboard'));
        }

        if ($user->hasRole('guru_bk')) {
            return redirect()->intended(route('bk.dashboard'));
        }

        if ($user->hasRole('siswa')) {
            return redirect()->intended(route('siswa.dashboard'));
        }

        if ($user->hasRole('orang_tua')) {
            return redirect()->intended(route('ortu.dashboard'));
        }

        return redirect()->intended('/');
    }
}
