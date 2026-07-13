<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'appointments_today' => Appointment::where('doctor_id', auth()->id())
                ->whereDate('scheduled_at', today())->count(),
            'appointments_month' => Appointment::where('doctor_id', auth()->id())
                ->whereMonth('created_at', now()->month)->count(),
            'completed_month' => Appointment::where('doctor_id', auth()->id())
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)->count(),
            'pending_today' => Appointment::where('doctor_id', auth()->id())
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->whereDate('scheduled_at', today())->count(),
        ];

        $upcoming = Appointment::where('doctor_id', auth()->id())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('scheduled_at', '>=', now())
            ->with('patient', 'specialty')
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        return view('doctor.dashboard', compact('stats', 'upcoming'));
    }
}
