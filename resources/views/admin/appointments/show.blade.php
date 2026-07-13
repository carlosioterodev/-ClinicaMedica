@extends('layouts.app')

@section('title', 'Cita #' . $appointment->id . ' - Admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Cita #{{ $appointment->id }}</h1>
    <a href="{{ route('admin.appointments.index') }}" class="text-blue-600 hover:underline">← Volver</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Info de la cita -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Detalles de la Cita</h2>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Paciente</dt>
                    <dd><a href="{{ route('patient.show', $appointment->patient) }}" class="text-blue-600 hover:underline">{{ $appointment->patient->name }}</a></dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Médico</dt>
                    <dd>Dr(a). {{ $appointment->doctor->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Especialidad</dt>
                    <dd>{{ $appointment->specialty->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Fecha y Hora</dt>
                    <dd>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Duración</dt>
                    <dd>{{ $appointment->duration_minutes }} minutos</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Estado</dt>
                    <dd>
                        <span class="px-2 inline-flex text-xs rounded-full font-medium
                            {{ match($appointment->status) {
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ match($appointment->status) { 'scheduled' => 'Programada', 'confirmed' => 'Confirmada', 'in_progress' => 'En Progreso', 'completed' => 'Completada', 'cancelled' => 'Cancelada', 'no_show' => 'No Asistió', default => $appointment->status } }}
                        </span>
                    </dd>
                </div>
                <div class="col-span-2">
                    <dt class="font-medium text-gray-500">Motivo</dt>
                    <dd>{{ $appointment->reason ?: 'No especificado' }}</dd>
                </div>
                @if($appointment->cancellation_reason)
                    <div class="col-span-2 bg-red-50 p-3 rounded">
                        <dt class="font-medium text-red-600 text-xs">Motivo de Cancelación</dt>
                        <dd class="text-red-700 text-sm">{{ $appointment->cancellation_reason }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <!-- Notas -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Notas de la Cita</h2>
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
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Acciones</h2>
            <div class="space-y-2">
                <a href="{{ route('patient.show', $appointment->patient) }}" class="block w-full text-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm transition">
                    Ver Perfil del Paciente
                </a>
            </div>
        </div>

        @if($appointment->medicalRecord)
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Registro Médico</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Diagnóstico:</dt>
                        <dd>{{ $appointment->medicalRecord->diagnosis }}</dd>
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
                </dl>
            </div>
        @endif
    </div>
</div>
@endsection
