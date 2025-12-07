<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (in_array($user->role, ['Admin', 'HRD'])) {
            return $next($request);
        }

        if (!$user->profile || $user->profile->status_aktif == false) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Akun non-aktif, kamu tidak bisa mengakses fitur cuti.');
        }

        return $next($request);
    }
}
