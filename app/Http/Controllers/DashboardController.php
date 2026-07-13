<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        }
        if ($user->hasRole('nurse')) {
            return redirect()->route('nurse.dashboard');
        }
        if ($user->hasRole('patient')) {
            return redirect()->route('patient.dashboard');
        }

        return view('dashboard');
    }
}
