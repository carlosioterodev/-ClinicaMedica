@extends('layouts.app')

@section('title', 'Registro Médico #' . $record->id)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Registro Médico</h1>
        <p class="text-gray-500 text-sm">{{ $record->patient->name }} — {{ $record->created_at->format('d/m/Y') }}</p>
    </div>
    <a href="{{ route('doctor.medical-records.index') }}" class="text-blue-600 hover:underline">← Volver</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Diagnóstico -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos Clínicos</h2>
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Diagnóstico:</dt>
                    <dd class="text-lg mt-1">{{ $record->diagnosis }}</dd>
                </div>
                @if($record->symptoms)
                    <div>
                        <dt class="font-medium text-gray-500">Síntomas:</dt>
                        <dd class="text-gray-600 mt-1">{{ $record->symptoms }}</dd>
                    </div>
                @endif
                @if($record->treatment)
                    <div>
                        <dt class="font-medium text-gray-500">Tratamiento:</dt>
                        <dd class="text-gray-600 mt-1">{{ $record->treatment }}</dd>
                    </div>
                @endif
                @if($record->notes)
                    <div>
                        <dt class="font-medium text-gray-500">Notas:</dt>
                        <dd class="text-gray-600 mt-1 italic">{{ $record->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <!-- Recetas -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-semibold">Recetas Médicas</h2>
                <button onclick="document.getElementById('add-prescription').classList.toggle('hidden')" class="text-blue-600 hover:underline text-sm">
                    + Agregar Receta
                </button>
            </div>

            <!-- Form nueva receta -->
            <div id="add-prescription" class="hidden mb-4 bg-gray-50 p-4 rounded-lg">
                <form method="POST" action="{{ route('doctor.medical-records.prescriptions.store', $record) }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="medication_name" placeholder="Nombre del medicamento" required class="w-full border-gray-300 rounded-lg text-sm">
                        <input type="text" name="dosage" placeholder="Dosis (ej: 500mg)" required class="w-full border-gray-300 rounded-lg text-sm">
                        <input type="text" name="frequency" placeholder="Frecuencia (ej: cada 8h)" required class="w-full border-gray-300 rounded-lg text-sm">
                        <input type="number" name="duration_days" placeholder="Días" min="1" class="w-full border-gray-300 rounded-lg text-sm">
                    </div>
                    <input type="text" name="instructions" placeholder="Instrucciones especiales (opcional)" class="w-full border-gray-300 rounded-lg text-sm">
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Guardar Receta</button>
                </form>
            </div>

            <div class="space-y-3">
                @forelse($record->prescriptions as $rx)
                    <div class="flex justify-between items-start p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-sm">{{ $rx->medication->name ?? $rx->medication_name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $rx->dosage }} — {{ $rx->frequency }}
                                @if($rx->duration_days) × {{ $rx->duration_days }} días @endif
                            </p>
                            @if($rx->instructions)
                                <p class="text-xs text-gray-400 mt-1 italic">{{ $rx->instructions }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('doctor.medical-records.prescriptions.destroy', [$record, $rx]) }}" onsubmit="return confirm('¿Eliminar receta?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-xs">✕</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay recetas registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Información</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Cita:</dt>
                    <dd><a href="{{ route('doctor.appointments.show', $record->appointment) }}" class="text-blue-600 hover:underline">#{{ $record->appointment_id }}</a></dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500">Paciente:</dt>
                    <dd><a href="{{ route('patient.show', $record->patient) }}" class="text-blue-600 hover:underline">{{ $record->patient->name }}</a></dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500">Creado:</dt><dd>{{ $record->created_at->format('d/m/Y H:i') }}</dd></div>
            </dl>
        </div>
    </div>
</div>
@endsection
