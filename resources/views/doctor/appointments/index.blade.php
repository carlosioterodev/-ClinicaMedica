@extends('layouts.app')

@section('title', 'Mis Citas - Doctor')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mis Citas</h1>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($appointments as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $appointment->patient->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $appointment->specialty->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ $appointment->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-blue-600 hover:underline">Ver</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay citas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3">{{ $appointments->links() }}</div>
</div>
@endsection
