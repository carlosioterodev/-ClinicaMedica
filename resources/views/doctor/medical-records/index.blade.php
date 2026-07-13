@extends('layouts.app')

@section('title', 'Mis Registros Médicos')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mis Registros Médicos</h1>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cita</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diagnóstico</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($records as $record)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $record->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('patient.show', $record->patient) }}" class="text-blue-600 hover:underline">
                            {{ $record->patient->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-500">#{{ $record->appointment_id }}</td>
                    <td class="px-4 py-3">{{ Str::limit($record->diagnosis, 60) }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('doctor.medical-records.show', $record) }}" class="text-blue-600 hover:underline">Ver</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No hay registros médicos.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $records->links() }}</div>
</div>
@endsection
