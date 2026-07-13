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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath($request->user()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectPath($user): string
    {
        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('doctor')) {
            return route('doctor.dashboard');
        }
        if ($user->hasRole('nurse')) {
            return route('nurse.dashboard');
        }
        if ($user->hasRole('patient')) {
            return route('patient.dashboard');
        }

        return route('dashboard');
    }
}
