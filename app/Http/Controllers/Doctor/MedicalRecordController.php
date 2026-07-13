<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\AppointmentNote;
use App\Models\Prescription;
use App\Models\Medication;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::where('doctor_id', auth()->id())
            ->with('patient', 'appointment')
            ->latest()
            ->paginate(20);

        return view('doctor.medical-records.index', compact('records'));
    }

    public function show(MedicalRecord $record)
    {
        abort_unless($record->doctor_id === auth()->id(), 403);

        $record->load([
            'patient.profile', 'appointment', 'prescriptions.medication', 'labResults',
        ]);

        return view('doctor.medical-records.show', compact('record'));
    }

    public function createForAppointment(Appointment $appointment)
    {
        abort_unless($appointment->doctor_id === auth()->id(), 403);

        $appointment->load('patient.profile');
        $medications = Medication::where('is_active', true)->orderBy('name')->get();

        return view('doctor.medical-records.create', compact('appointment', 'medications'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->doctor_id === auth()->id(), 403);

        $validated = $request->validate([
            'diagnosis' => 'required|string|max:1000',
            'symptoms' => 'nullable|string|max:1000',
            'treatment' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);

        $record = MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => auth()->id(),
            ...$validated,
        ]);

        return redirect()->route('doctor.medical-records.show', $record)
            ->with('success', 'Registro médico creado.');
    }

    public function storeNote(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->doctor_id === auth()->id(), 403);

        $validated = $request->validate([
            'note_type' => 'required|in:clinical,administrative',
            'content' => 'required|string|max:2000',
        ]);

        $appointment->notes()->create([
            'author_id' => auth()->id(),
            ...$validated,
        ]);

        return back()->with('success', 'Nota agregada.');
    }

    public function storePrescription(Request $request, MedicalRecord $record)
    {
        abort_unless($record->doctor_id === auth()->id(), 403);

        $validated = $request->validate([
            'medication_id' => 'nullable|exists:medications,id',
            'medication_name' => 'required_without:medication_id|string|max:255',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'duration_days' => 'nullable|integer|min:1',
            'instructions' => 'nullable|string|max:500',
        ]);

        $validated['doctor_id'] = auth()->id();
        $validated['patient_id'] = $record->patient_id;
        $validated['medical_record_id'] = $record->id;
        $validated['status'] = 'active';

        $record->prescriptions()->create($validated);

        return back()->with('success', 'Receta agregada.');
    }

    public function destroyPrescription(MedicalRecord $record, Prescription $prescription)
    {
        abort_unless($record->doctor_id === auth()->id(), 403);
        abort_unless($prescription->medical_record_id === $record->id, 403);

        $prescription->delete();

        return back()->with('success', 'Receta eliminada.');
    }
}
