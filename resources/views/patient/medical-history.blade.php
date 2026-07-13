@extends('layouts.app')

@section('title', 'Historial Clínico - ' . $patient->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Historial Clínico</h1>
        <p class="text-gray-500 text-sm">{{ $patient->name }} — DNI: {{ $patient->profile->dni ?? 'N/A' }}</p>
    </div>
    <a href="{{ route('patient.show', $patient) }}" class="text-blue-600 hover:underline">← Volver al Perfil</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<!-- Tabs -->
<div x-data="{ tab: 'records' }" class="space-y-6">
    <div class="flex gap-1 bg-gray-100 p-1 rounded-lg w-fit">
        <button @click="tab = 'records'" :class="tab === 'records' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'" class="px-4 py-2 rounded-md text-sm font-medium transition">
            Historial Médico
        </button>
        <button @click="tab = 'vitals'" :class="tab === 'vitals' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600'" class="px-4 py-2 rounded-md text-sm font-medium transition">
            Signos Vitales
        </button>
    </div>

    <!-- Tab: Historial Médico -->
    <div x-show="tab === 'records'">
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diagnóstico</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tratamiento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($medicalRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $record->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">Dr(a). {{ $record->doctor->name }}</td>
                            <td class="px-4 py-3">{{ $record->diagnosis }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ Str::limit($record->treatment, 80) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">No hay registros médicos.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $medicalRecords->links() }}</div>
        </div>
    </div>

    <!-- Tab: Signos Vitales -->
    <div x-show="tab === 'vitals'">
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Temp.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">FC</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">PA</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peso</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alt.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">O₂</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IMC</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vitalSigns as $vs)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $vs->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $vs->temperature ? $vs->temperature . '°C' : '-' }}</td>
                            <td class="px-4 py-3">{{ $vs->heart_rate ? $vs->heart_rate . ' lpm' : '-' }}</td>
                            <td class="px-4 py-3">{{ $vs->systolic_pressure ? $vs->systolic_pressure . '/' . $vs->diastolic_pressure : '-' }}</td>
                            <td class="px-4 py-3">{{ $vs->weight ? $vs->weight . ' kg' : '-' }}</td>
                            <td class="px-4 py-3">{{ $vs->height ? $vs->height . ' cm' : '-' }}</td>
                            <td class="px-4 py-3">{{ $vs->oxygen_saturation ? $vs->oxygen_saturation . '%' : '-' }}</td>
                            <td class="px-4 py-3">{{ $vs->bmi ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $vs->recorder?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-4 text-center text-gray-500">No hay signos vitales registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $vitalSigns->links() }}</div>
        </div>
    </div>
</div>
@endsection
