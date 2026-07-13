@extends('layouts.app')

@section('title', 'Citas - Admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Gestión de Citas</h1>

<!-- Filtros -->
<div class="bg-white p-4 rounded-lg shadow-sm border mb-6">
    <form method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar paciente..."
               class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        <input type="date" name="date" value="{{ request('date') }}"
               class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        <select name="status" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todos los estados</option>
            @foreach(['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'] as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                    {{ match($status) { 'scheduled' => 'Programada', 'confirmed' => 'Confirmada', 'in_progress' => 'En Progreso', 'completed' => 'Completada', 'cancelled' => 'Cancelada', 'no_show' => 'No Asistió', default => $status } }}
                </option>
            @endforeach
        </select>
        <select name="doctor_id" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todos los médicos</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                    Dr(a). {{ $doctor->name }}
                </option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filtrar</button>
            <a href="{{ route('admin.appointments.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">Limpiar</a>
        </div>
    </form>
</div>

<!-- Tabla -->
<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médico</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialidad</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($appointments as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('patient.show', $appointment->patient) }}" class="text-blue-600 hover:underline">
                            {{ $appointment->patient->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3">Dr(a). {{ $appointment->doctor->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $appointment->specialty->name }}</td>
                    <td class="px-4 py-3">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 inline-flex text-xs rounded-full font-medium
                            {{ match($appointment->status) {
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                'no_show' => 'bg-gray-200 text-gray-700',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ match($appointment->status) { 'scheduled' => 'Programada', 'confirmed' => 'Confirmada', 'in_progress' => 'En Progreso', 'completed' => 'Completada', 'cancelled' => 'Cancelada', 'no_show' => 'No Asistió', default => $appointment->status } }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-blue-600 hover:underline">Ver</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No hay citas que mostrar.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $appointments->links() }}</div>
</div>
@endsection
