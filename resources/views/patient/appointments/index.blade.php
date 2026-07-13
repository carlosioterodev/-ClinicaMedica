@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Mis Citas</h1>
    <a href="{{ route('patient.appointments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        + Nueva Cita
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médico</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($appointments as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">Dr(a). {{ $appointment->doctor->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $appointment->specialty->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ $appointment->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                            <form method="POST" action="{{ route('patient.appointments.cancel', $appointment->id) }}" class="inline" onsubmit="return confirm('¿Cancelar esta cita?')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="cancellation_reason" value="Cancelada por el paciente">
                                <button class="text-red-600 hover:underline">Cancelar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No tienes citas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3">{{ $appointments->links() }}</div>
</div>
@endsection
