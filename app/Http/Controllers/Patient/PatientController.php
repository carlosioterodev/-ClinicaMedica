<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VitalSign;
use App\Models\PatientAllergy;
use App\Models\PatientChronicCondition;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function show(User $patient)
    {
        abort_unless($patient->id === auth()->id() || auth()->user()->hasRole('admin') || auth()->user()->hasRole('doctor'), 403);

        $patient->load([
            'profile', 'allergies', 'chronicConditions', 'vitalSigns.recorder',
            'medicalRecords' => fn ($q) => $q->latest()->limit(10),
            'appointmentsAsPatient' => fn ($q) => $q->with('doctor', 'specialty')->latest()->limit(10),
        ]);

        $latestVitalSign = $patient->vitalSigns()->latest()->first();

        return view('patient.show', compact('patient', 'latestVitalSign'));
    }

    public function medicalHistory(User $patient)
    {
        abort_unless($patient->id === auth()->id() || auth()->user()->hasRole('admin') || auth()->user()->hasRole('doctor'), 403);

        $medicalRecords = $patient->medicalRecords()
            ->with('doctor', 'appointment')
            ->latest()
            ->paginate(15);

        $vitalSigns = $patient->vitalSigns()
            ->with('recorder')
            ->latest()
            ->paginate(15);

        return view('patient.medical-history', compact('patient', 'medicalRecords', 'vitalSigns'));
    }

    public function storeAllergy(Request $request, User $patient)
    {
        abort_unless($patient->id === auth()->id(), 403);

        $validated = $request->validate([
            'allergen' => 'required|string|max:255',
            'severity' => 'required|in:mild,moderate,severe',
            'reaction' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $patient->allergies()->create($validated);

        return back()->with('success', 'Alergia registrada.');
    }

    public function destroyAllergy(User $patient, PatientAllergy $allergy)
    {
        abort_unless($patient->id === auth()->id(), 403);

        $allergy->delete();

        return back()->with('success', 'Alergia eliminada.');
    }

    public function storeCondition(Request $request, User $patient)
    {
        abort_unless($patient->id === auth()->id(), 403);

        $validated = $request->validate([
            'condition_name' => 'required|string|max:255',
            'diagnosis_date' => 'nullable|string|max:100',
            'treatment' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $patient->chronicConditions()->create($validated);

        return back()->with('success', 'Condición crónica registrada.');
    }

    public function destroyCondition(User $patient, PatientChronicCondition $condition)
    {
        abort_unless($patient->id === auth()->id(), 403);

        $condition->delete();

        return back()->with('success', 'Condición eliminada.');
    }
}
