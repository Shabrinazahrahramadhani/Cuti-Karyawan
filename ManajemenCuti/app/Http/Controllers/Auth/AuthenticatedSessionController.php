<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate user
        $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        // Check user role and redirect to the respective dashboard
        if ($request->user()->role === 'Admin') {
            $url = route('admin.dashboard');
        } elseif ($request->user()->role === 'HRD') {
            $url = route('hrd.dashboard');
        } elseif ($request->user()->role === 'Leader') {
            $url = route('leader.dashboard');
        } elseif ($request->user()->role === 'User') {
            $url = route('user.dashboard');
        } else {
            $url = '/';  // Fallback if no role matches
        }

        return redirect()->intended($url);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
