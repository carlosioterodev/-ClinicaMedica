<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with('patient', 'doctor', 'specialty')
            ->when($request->date, fn ($q) => $q->whereDate('scheduled_at', $request->date))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->doctor_id, fn ($q) => $q->where('doctor_id', $request->doctor_id))
            ->when($request->search, fn ($q) => $q->whereHas('patient', fn ($p) => $p->where('name', 'like', "%{$request->search}%")))
            ->latest('scheduled_at')
            ->paginate(20)
            ->withQueryString();

        $doctors = \App\Models\User::whereHas('roles', fn ($q) => $q->where('name', 'doctor'))->get();

        return view('admin.appointments.index', compact('appointments', 'doctors'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.profile', 'doctor', 'specialty', 'notes.author', 'medicalRecord']);

        return view('admin.appointments.show', compact('appointment'));
    }
}
