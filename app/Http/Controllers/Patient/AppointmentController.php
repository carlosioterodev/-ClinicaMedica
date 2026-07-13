<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\User;
use App\Services\Appointment\Contracts\AppointmentServiceInterface;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentServiceInterface $appointmentService
    ) {}

    public function index()
    {
        $appointments = auth()->user()->appointmentsAsPatient()
            ->with('doctor', 'specialty')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $specialties = Specialty::where('is_active', true)->get();
        return view('patient.appointments.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
            'scheduled_at' => 'required|date|after:now',
            'reason' => 'nullable|string|max:500',
        ]);

        $validated['patient_id'] = auth()->id();
        $validated['duration_minutes'] = 30;
        $validated['created_by'] = auth()->id();

        $this->appointmentService->create($validated);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Cita agendada correctamente.');
    }

    public function cancel(Request $request, $id)
    {
        $appointment = auth()->user()->appointmentsAsPatient()->findOrFail($id);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $this->appointmentService->cancel($appointment, $request->cancellation_reason);

        return back()->with('success', 'Cita cancelada.');
    }

    public function availableSlots(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after:today',
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $doctor = User::findOrFail($validated['doctor_id']);
        $date = \Carbon\Carbon::parse($validated['date']);

        $slots = $this->appointmentService->getAvailableSlots($doctor, $date, $validated['specialty_id']);

        return response()->json($slots);
    }

    public function invoices()
    {
        $invoices = auth()->user()->invoices()
            ->with('doctor')
            ->latest()
            ->paginate(15);

        return view('patient.invoices.index', compact('invoices'));
    }
}
