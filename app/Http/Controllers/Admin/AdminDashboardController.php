<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Specialty;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => User::role('patient')->count(),
            'total_doctors' => User::role('doctor')->count(),
            'appointments_today' => Appointment::whereDate('scheduled_at', today())->count(),
            'appointments_week' => Appointment::whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'appointments_month' => Appointment::whereMonth('created_at', now()->month)->count(),
            'revenue_month' => Invoice::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)->sum('total'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
        ];

        $todayAppointments = Appointment::with('patient', 'doctor', 'specialty')
            ->whereDate('scheduled_at', today())
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        $recentUsers = User::with('roles')->latest()->take(5)->get();

        $recentInvoices = Invoice::with('patient')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'todayAppointments', 'recentUsers', 'recentInvoices'));
    }
}
