<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->date_from ? now()->parse($request->date_from) : now()->startOfMonth();
        $dateTo = $request->date_to ? now()->parse($request->date_to) : now()->endOfMonth();

        $stats = [
            'total_patients' => User::role('patient')->count(),
            'total_doctors' => User::role('doctor')->count(),
            'appointments_today' => Appointment::whereDate('scheduled_at', today())->count(),
            'appointments_month' => Appointment::whereBetween('scheduled_at', [$dateFrom, $dateTo])->count(),
            'revenue_month' => Invoice::where('status', 'paid')
                ->whereBetween('paid_at', [$dateFrom, $dateTo])->sum('total'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
        ];

        $appointmentsByStatus = Appointment::whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $appointmentsBySpecialty = Appointment::whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->join('specialties', 'appointments.specialty_id', '=', 'specialties.id')
            ->select('specialties.name as specialty', DB::raw('count(*) as total'))
            ->groupBy('specialties.name')
            ->pluck('total', 'specialty');

        $appointmentsByMonth = Appointment::where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $revenueByMonth = Invoice::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(total) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $topDoctors = Appointment::whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->where('appointments.status', 'completed')
            ->join('users', 'appointments.doctor_id', '=', 'users.id')
            ->select('users.name as doctor', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'doctor');

        $recentInvoices = Invoice::with('patient')
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'stats', 'appointmentsByStatus', 'appointmentsBySpecialty',
            'appointmentsByMonth', 'revenueByMonth', 'topDoctors',
            'recentInvoices', 'dateFrom', 'dateTo'
        ));
    }
}
