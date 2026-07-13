<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\VitalSign;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    public function index()
    {
        $todayAppointments = Appointment::with('patient.profile', 'doctor', 'specialty')
            ->whereDate('scheduled_at', today())
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->orderBy('scheduled_at')
            ->get();

        $stats = [
            'waiting' => $todayAppointments->where('status', 'scheduled')->count(),
            'confirmed' => $todayAppointments->where('status', 'confirmed')->count(),
            'in_progress' => $todayAppointments->where('status', 'in_progress')->count(),
            'completed_today' => Appointment::where('doctor_id', '!=', null)
                ->whereDate('scheduled_at', today())
                ->where('status', 'completed')->count(),
        ];

        return view('nurse.dashboard', compact('todayAppointments', 'stats'));
    }

    public function showPatient(Appointment $appointment)
    {
        $appointment->load('patient.profile', 'patient.allergies', 'patient.chronicConditions', 'doctor', 'specialty');

        $latestVitalSign = VitalSign::where('patient_id', $appointment->patient_id)
            ->latest()->first();

        return view('nurse.patient-detail', compact('appointment', 'latestVitalSign'));
    }

    public function storeVitalSigns(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'temperature' => 'nullable|numeric|min:30|max:45',
            'heart_rate' => 'nullable|integer|min:30|max:250',
            'systolic_pressure' => 'nullable|integer|min:50|max:300',
            'diastolic_pressure' => 'nullable|integer|min:20|max:200',
            'weight' => 'nullable|numeric|min:0.5|max:500',
            'height' => 'nullable|numeric|min:30|max:280',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        VitalSign::create([
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'recorded_by' => auth()->id(),
            ...$validated,
        ]);

        return back()->with('success', 'Signos vitales registrados.');
    }

    public function triage(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        $request->validate([
            'content' => 'required|string|max:2000',
            'priority' => 'nullable|in:low,normal,high,urgent',
        ]);

        $appointment->notes()->create([
            'author_id' => auth()->id(),
            'note_type' => 'triage',
            'content' => ($request->priority ? "[{$request->priority}] " : '') . $request->content,
        ]);

        $appointment->update(['status' => 'confirmed']);

        return back()->with('success', 'Triage registrado y cita confirmada.');
    }
}
