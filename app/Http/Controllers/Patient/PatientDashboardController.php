<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'upcoming_appointments' => Appointment::where('patient_id', $userId)
                ->whereIn('status', ['scheduled', 'confirmed'])->count(),
            'completed_appointments' => Appointment::where('patient_id', $userId)
                ->where('status', 'completed')->count(),
            'pending_invoices' => Invoice::where('patient_id', $userId)
                ->where('status', 'pending')->count(),
        ];

        $nextAppointment = Appointment::where('patient_id', $userId)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('scheduled_at', '>=', now())
            ->with('doctor', 'specialty')
            ->orderBy('scheduled_at')
            ->first();

        $recentAppointments = Appointment::where('patient_id', $userId)
            ->with('doctor', 'specialty')
            ->latest()
            ->limit(5)
            ->get();

        $pendingInvoices = Invoice::where('patient_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact('stats', 'nextAppointment', 'recentAppointments', 'pendingInvoices'));
    }
}
