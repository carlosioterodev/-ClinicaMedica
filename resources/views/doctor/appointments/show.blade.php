@extends('layouts.app')

@section('title', 'Cita #' . $appointment->id . ' - Doctor')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Cita #{{ $appointment->id }}</h1>
    <a href="{{ route('doctor.appointments.index') }}" class="text-blue-600 hover:underline">← Volver</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Info de la cita -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Detalles de la Cita</h2>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Paciente</dt>
                    <dd>
                        <a href="{{ route('patient.show', $appointment->patient) }}" class="text-blue-600 hover:underline">
                            {{ $appointment->patient->name }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Especialidad</dt>
                    <dd>{{ $appointment->specialty->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Fecha/Hora</dt>
                    <dd>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Duración</dt>
                    <dd>{{ $appointment->duration_minutes }} min</dd>
                </div>
                <div class="col-span-2">
                    <dt class="font-medium text-gray-500">Motivo</dt>
                    <dd>{{ $appointment->reason ?: 'No especificado' }}</dd>
                </div>
                @if($appointment->cancellation_reason)
                    <div class="col-span-2 bg-red-50 p-3 rounded">
                        <dt class="font-medium text-red-600 text-xs">Cancelación:</dt>
                        <dd class="text-red-700 text-sm">{{ $appointment->cancellation_reason }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <!-- Notas de la cita -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Notas</h2>
            @forelse($appointment->notes as $note)
                <div class="border-b last:border-0 py-3">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium">{{ $note->author->name }}</span>
                        <span class="text-xs text-gray-400">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ match($note->note_type) { 'clinical' => 'bg-blue-100 text-blue-800', 'administrative' => 'bg-gray-100 text-gray-800', default => 'bg-gray-100 text-gray-800' } }}">
                        {{ ucfirst($note->note_type) }}
                    </span>
                    <p class="text-sm text-gray-600 mt-2">{{ $note->content }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No hay notas registradas.</p>
            @endforelse
        </div>

        <!-- Registro Médico -->
        @if($appointment->medicalRecord)
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Registro Médico</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Diagnóstico:</dt>
                        <dd class="text-lg">{{ $appointment->medicalRecord->diagnosis }}</dd>
                    </div>
                    @if($appointment->medicalRecord->symptoms)
                        <div>
                            <dt class="font-medium text-gray-500">Síntomas:</dt>
                            <dd class="text-gray-600">{{ $appointment->medicalRecord->symptoms }}</dd>
                        </div>
                    @endif
                    @if($appointment->medicalRecord->treatment)
                        <div>
                            <dt class="font-medium text-gray-500">Tratamiento:</dt>
                            <dd class="text-gray-600">{{ $appointment->medicalRecord->treatment }}</dd>
                        </div>
                    @endif
                    @if($appointment->medicalRecord->prescriptions->count())
                        <div class="border-t pt-3 mt-3">
                            <dt class="font-medium text-gray-500 mb-2">Recetas:</dt>
                            @foreach($appointment->medicalRecord->prescriptions as $rx)
                                <div class="bg-gray-50 p-2 rounded mb-2 text-sm">
                                    {{ $rx->medication->name ?? 'Medicamento' }} — {{ $rx->dosage }} × {{ $rx->frequency }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </dl>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Cambiar estado -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Acciones</h2>
            <div class="space-y-2 mb-4">
                @if(!$appointment->medicalRecord)
                    <a href="{{ route('doctor.medical-records.create', $appointment) }}" class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm transition">
                        Crear Registro Médico
                    </a>
                @else
                    <a href="{{ route('doctor.medical-records.show', $appointment->medicalRecord) }}" class="block w-full text-center bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 text-sm transition">
                        Ver Registro Médico
                    </a>
                @endif
            </div>
            <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}">
                @csrf @method('PUT')
                <div class="mb-4">
                    <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach(['scheduled' => 'Programada', 'confirmed' => 'Confirmada', 'in_progress' => 'En Progreso', 'completed' => 'Completada', 'no_show' => 'No Asistió'] as $val => $label)
                            <option value="{{ $val }}" {{ $appointment->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                    Actualizar Estado
                </button>
            </form>
        </div>

        <!-- Datos del paciente -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos del Paciente</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">DNI:</dt>
                    <dd>{{ $appointment->patient->profile->dni ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Edad:</dt>
                    <dd>{{ $appointment->patient->profile->age ?? 'N/A' }} años</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Teléfono:</dt>
                    <dd>{{ $appointment->patient->profile->phone ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Tipo Sangre:</dt>
                    <dd>{{ $appointment->patient->profile->blood_type ?? 'N/A' }}</dd>
                </div>
            </dl>
            @if($appointment->patient->allergies->count())
                <div class="mt-3 border-t pt-3">
                    <dt class="font-medium text-gray-500 text-xs mb-2">⚠ Alergias:</dt>
                    <div class="flex flex-wrap gap-1">
                        @foreach($appointment->patient->allergies->where('is_active', true) as $allergy)
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ match($allergy->severity) { 'severe' => 'bg-red-100 text-red-800', 'moderate' => 'bg-orange-100 text-orange-800', default => 'bg-yellow-100 text-yellow-800' } }}">
                                {{ $allergy->allergen }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
