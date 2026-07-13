@extends('layouts.app')

@section('title', 'Panel de Enfermería')

@section('content')
<h1 class="text-2xl font-bold mb-6">Panel de Enfermería</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">En Espera</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['waiting'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Confirmadas</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">En Atención</p>
        <p class="text-2xl font-bold text-purple-600">{{ $stats['in_progress'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Completadas Hoy</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['completed_today'] }}</p>
    </div>
</div>

<!-- Cola de pacientes -->
<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold">Cola de Pacientes — {{ now()->format('d/m/Y') }}</h2>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($todayAppointments as $appointment)
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                            {{ substr($appointment->patient->name, 0, 2) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $appointment->patient->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $appointment->specialty->name }} — Dr(a). {{ $appointment->doctor->name }}
                            </p>
                            @if($appointment->patient->profile)
                                <p class="text-xs text-gray-400 mt-0.5">
                                    DNI: {{ $appointment->patient->profile->dni ?? 'N/A' }}
                                    @if($appointment->patient->profile->age) — {{ $appointment->patient->profile->age }} años @endif
                                    @if($appointment->patient->profile->blood_type) — Sangre: {{ $appointment->patient->profile->blood_type }} @endif
                                </p>
                            @endif
                            @if($appointment->patient->allergies->count())
                                <div class="flex gap-1 mt-1">
                                    @foreach($appointment->patient->allergies->where('is_active', true) as $allergy)
                                        <span class="text-xs px-1.5 py-0.5 rounded bg-red-100 text-red-700">⚠ {{ $allergy->allergen }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">{{ $appointment->scheduled_at->format('H:i') }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ match($appointment->status) {
                                    'scheduled' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800',
                                } }}">
                                {{ match($appointment->status) { 'scheduled' => 'En Espera', 'confirmed' => 'Confirmada', 'in_progress' => 'En Atención', default => $appointment->status } }}
                            </span>
                        </div>

                        <a href="{{ route('nurse.patient-detail', $appointment) }}"
                           class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700 transition">
                            Triaje
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center text-gray-500">
                <p class="text-lg mb-1">No hay pacientes en cola hoy.</p>
                <p class="text-sm">Todas las citas del día han sido atendidas o no hay citas programadas.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
