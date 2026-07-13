<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\TimeOff;
use App\Services\Appointment\Contracts\AppointmentServiceInterface;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentServiceInterface $appointmentService
    ) {}

    public function index(Request $request)
    {
        $appointments = Appointment::where('doctor_id', auth()->id())
            ->with('patient', 'specialty')
            ->when($request->date, fn ($q) => $q->whereDate('scheduled_at', $request->date))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('scheduled_at')
            ->paginate(20);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.profile', 'patient.allergies', 'specialty', 'notes.author', 'medicalRecord.prescriptions.medication']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,in_progress,completed,no_show',
        ]);

        $this->appointmentService->updateStatus($appointment, $validated['status']);

        return back()->with('success', 'Estado actualizado.');
    }
}
