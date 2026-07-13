@extends('layouts.app')

@section('title', 'Tiempo Libre')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Tiempo Libre</h1>
    <a href="{{ route('doctor.schedule.index') }}" class="text-blue-600 hover:underline">← Volver al Horario</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Registrar Tiempo Libre</h2>
            <form method="POST" action="{{ route('doctor.time-off.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicio</label>
                        <input type="time" name="start_time" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Vacío = día completo</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fin</label>
                        <input type="time" name="end_time" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                    <textarea name="reason" rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Opcional..."></textarea>
                </div>
                <button type="submit" class="w-full bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition text-sm">
                    Registrar Tiempo Libre
                </button>
            </form>
        </div>
    </div>

    <!-- Lista -->
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Mis Permisos</h2>
            <div class="space-y-3">
                @forelse($timeOffs as $timeOff)
                    <div class="flex items-center justify-between p-4 rounded-lg border
                        {{ match($timeOff->status) { 'approved' => 'bg-green-50 border-green-200', 'pending' => 'bg-yellow-50 border-yellow-200', 'rejected' => 'bg-red-50 border-red-200', default => 'bg-gray-50 border-gray-200' } }}">
                        <div>
                            <p class="font-medium text-sm">{{ $timeOff->date->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($timeOff->start_time && $timeOff->end_time)
                                    {{ $timeOff->start_time }} → {{ $timeOff->end_time }}
                                @else
                                    Día completo
                                @endif
                                @if($timeOff->reason) — {{ $timeOff->reason }} @endif
                            </p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full font-medium
                            {{ match($timeOff->status) { 'approved' => 'bg-green-100 text-green-800', 'pending' => 'bg-yellow-100 text-yellow-800', 'rejected' => 'bg-red-100 text-red-800', default => 'bg-gray-100 text-gray-800' } }}">
                            {{ match($timeOff->status) { 'approved' => 'Aprobado', 'pending' => 'Pendiente', 'rejected' => 'Rechazado', default => ucfirst($timeOff->status) } }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No hay permisos registrados.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
