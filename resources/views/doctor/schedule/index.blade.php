@extends('layouts.app')

@section('title', 'Mi Horario')

@section('content')
<h1 class="text-2xl font-bold mb-6">Gestión de Horario Semanal</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Agregar / Editar Horario</h2>
            <form method="POST" action="{{ route('doctor.schedule.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Día de la Semana <span class="text-red-500">*</span></label>
                    <select name="day_of_week" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                        <option value="7">Domingo</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicio <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" value="08:00" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fin <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" value="17:00" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duración Cita (min) <span class="text-red-500">*</span></label>
                    <select name="slot_duration_minutes" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="15">15 minutos</option>
                        <option value="20">20 minutos</option>
                        <option value="30" selected>30 minutos</option>
                        <option value="45">45 minutos</option>
                        <option value="60">60 minutos</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                    Guardar Horario
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border mt-6">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Tip</h2>
            <p class="text-sm text-gray-600">
                Selecciona un día y configura tu horario de atención. Si ya tienes un horario para ese día, se actualizará automáticamente.
            </p>
        </div>
    </div>

    <!-- Vista semanal -->
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Tu Horario Semanal</h2>

            @php
                $days = [
                    1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
                    4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo',
                ];
                $schedulesByDay = $schedules->keyBy('day_of_week');
            @endphp

            <div class="space-y-3">
                @foreach($days as $num => $name)
                    @if(isset($schedulesByDay[$num]))
                        @php $s = $schedulesByDay[$num]; @endphp
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center gap-4">
                                <span class="w-28 font-semibold text-sm text-green-800">{{ $name }}</span>
                                <div class="text-sm">
                                    <span class="font-medium">{{ $s->start_time }}</span>
                                    <span class="text-gray-400 mx-1">→</span>
                                    <span class="font-medium">{{ $s->end_time }}</span>
                                    <span class="text-gray-400 ml-2 text-xs">(cada {{ $s->slot_duration_minutes }} min)</span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('doctor.schedule.destroy', $s) }}" onsubmit="return confirm('¿Eliminar horario de {{ $name }}?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="w-28 font-medium text-sm text-gray-400">{{ $name }}</span>
                            <span class="text-xs text-gray-400 italic">Sin horario configurado</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
