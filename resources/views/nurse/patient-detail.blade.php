@extends('layouts.app')

@section('title', 'Triaje - ' . $appointment->patient->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Triaje</h1>
        <p class="text-gray-500 text-sm">{{ $appointment->patient->name }} — Cita #{{ $appointment->id }} — {{ $appointment->scheduled_at->format('H:i') }}</p>
    </div>
    <a href="{{ route('nurse.dashboard') }}" class="text-blue-600 hover:underline">← Volver</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Formulario Signos Vitales -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Signos Vitales</h2>
            <form method="POST" action="{{ route('nurse.vital-signs.store', $appointment) }}">
                @csrf
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Temperatura (°C)</label>
                        <input type="number" name="temperature" step="0.1" min="30" max="45"
                               value="{{ old('temperature') }}" placeholder="36.5"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Frec. Cardíaca (lpm)</label>
                        <input type="number" name="heart_rate" min="30" max="250"
                               value="{{ old('heart_rate') }}" placeholder="72"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Presión Sistólica</label>
                        <input type="number" name="systolic_pressure" min="50" max="300"
                               value="{{ old('systolic_pressure') }}" placeholder="120"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Presión Diastólica</label>
                        <input type="number" name="diastolic_pressure" min="20" max="200"
                               value="{{ old('diastolic_pressure') }}" placeholder="80"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Peso (kg)</label>
                        <input type="number" name="weight" step="0.1" min="0.5" max="500"
                               value="{{ old('weight') }}" placeholder="70"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Altura (cm)</label>
                        <input type="number" name="height" step="0.1" min="30" max="280"
                               value="{{ old('height') }}" placeholder="170"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Saturación O₂ (%)</label>
                        <input type="number" name="oxygen_saturation" step="0.1" min="50" max="100"
                               value="{{ old('oxygen_saturation') }}" placeholder="98"
                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Notas</label>
                    <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Observaciones adicionales...">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Registrar Signos Vitales
                </button>
            </form>
        </div>

        <!-- Formulario Triage -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Registro de Triage</h2>
            <form method="POST" action="{{ route('nurse.triage', $appointment) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Prioridad</label>
                    <select name="priority" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="low">Baja</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Observaciones del Triage <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="4" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe el motivo de la consulta, síntomas principales, nivel de dolor...">{{ old('content') }}</textarea>
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                    Registrar Triage y Confirmar Cita
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos del Paciente</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Nombre:</dt><dd>{{ $appointment->patient->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">DNI:</dt><dd>{{ $appointment->patient->profile->dni ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Edad:</dt><dd>{{ $appointment->patient->profile->age ?? 'N/A' }} años</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Género:</dt><dd>{{ match($appointment->patient->profile->gender ?? '') { 'M' => 'M', 'F' => 'F', 'O' => 'Otro', default => 'N/A' } }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Sangre:</dt><dd>{{ $appointment->patient->profile->blood_type ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Teléfono:</dt><dd>{{ $appointment->patient->profile->phone ?? 'N/A' }}</dd></div>
            </dl>
        </div>

        @if($latestVitalSign)
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Últimos Signos Vitales</h2>
                <dl class="space-y-2 text-sm">
                    @if($latestVitalSign->temperature)
                        <div class="flex justify-between"><dt class="text-gray-500">Temp:</dt><dd>{{ $latestVitalSign->temperature }}°C</dd></div>
                    @endif
                    @if($latestVitalSign->heart_rate)
                        <div class="flex justify-between"><dt class="text-gray-500">FC:</dt><dd>{{ $latestVitalSign->heart_rate }} lpm</dd></div>
                    @endif
                    @if($latestVitalSign->systolic_pressure)
                        <div class="flex justify-between"><dt class="text-gray-500">PA:</dt><dd>{{ $latestVitalSign->systolic_pressure }}/{{ $latestVitalSign->diastolic_pressure }}</dd></div>
                    @endif
                    @if($latestVitalSign->weight)
                        <div class="flex justify-between"><dt class="text-gray-500">Peso:</dt><dd>{{ $latestVitalSign->weight }} kg</dd></div>
                    @endif
                    @if($latestVitalSign->bmi)
                        <div class="flex justify-between"><dt class="text-gray-500">IMC:</dt><dd>{{ $latestVitalSign->bmi }}</dd></div>
                    @endif
                </dl>
                <p class="text-xs text-gray-400 mt-2">{{ $latestVitalSign->created_at->format('d/m/Y H:i') }}</p>
            </div>
        @endif

        @if($appointment->patient->allergies->count())
            <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                <h2 class="text-lg font-semibold mb-2 text-red-800">⚠ Alergias</h2>
                <div class="space-y-1">
                    @foreach($appointment->patient->allergies->where('is_active', true) as $allergy)
                        <div class="text-sm">
                            <span class="font-medium text-red-700">{{ $allergy->allergen }}</span>
                            <span class="text-xs text-red-500">({{ match($allergy->severity) { 'severe' => 'Severo', 'moderate' => 'Moderado', default => 'Leve' } })</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($appointment->patient->chronicConditions->count())
            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                <h2 class="text-lg font-semibold mb-2 text-yellow-800">Condiciones Crónicas</h2>
                <div class="space-y-1">
                    @foreach($appointment->patient->chronicConditions->where('is_active', true) as $condition)
                        <p class="text-sm text-yellow-700">{{ $condition->condition_name }}</p>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
