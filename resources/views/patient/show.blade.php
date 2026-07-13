@extends('layouts.app')

@section('title', $patient->name . ' - Perfil')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold">{{ $patient->name }}</h1>
        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Paciente</span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('patient.medical-history', $patient) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
            Historial Clínico
        </a>
        @if(auth()->id() === $patient->id)
            <a href="{{ route('profile.edit') }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 text-sm">
                Editar Perfil
            </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna 1: Datos Personales -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos Personales</h2>
            @if($patient->profile)
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">DNI:</dt>
                        <dd>{{ $patient->profile->dni ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Teléfono:</dt>
                        <dd>{{ $patient->profile->phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Fecha Nac.:</dt>
                        <dd>{{ $patient->profile->date_of_birth?->format('d/m/Y') ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Edad:</dt>
                        <dd>{{ $patient->profile->age ?? 'N/A' }} años</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Género:</dt>
                        <dd>{{ match($patient->profile->gender ?? '') { 'M' => 'Masculino', 'F' => 'Femenino', 'O' => 'Otro', default => 'N/A' } }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Tipo Sangre:</dt>
                        <dd>{{ $patient->profile->blood_type ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Dirección:</dt>
                        <dd class="text-right max-w-[200px]">{{ $patient->profile->address ?? 'N/A' }}</dd>
                    </div>
                    @if($patient->profile->emergency_contact_name)
                        <div class="border-t pt-3 mt-3">
                            <dt class="font-medium text-gray-500 mb-1">Contacto de Emergencia:</dt>
                            <dd>{{ $patient->profile->emergency_contact_name }}</dd>
                            <dd class="text-gray-400">{{ $patient->profile->emergency_contact_phone }}</dd>
                        </div>
                    @endif
                </dl>
            @else
                <p class="text-gray-500 text-sm">No hay perfil registrado.</p>
            @endif
        </div>

        <!-- Signos Vitales -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Últimos Signos Vitales</h2>
            @if($latestVitalSign)
                <dl class="space-y-2 text-sm">
                    @if($latestVitalSign->temperature)
                        <div class="flex justify-between"><dt class="text-gray-500">Temperatura:</dt><dd>{{ $latestVitalSign->temperature }}°C</dd></div>
                    @endif
                    @if($latestVitalSign->heart_rate)
                        <div class="flex justify-between"><dt class="text-gray-500">Frecuencia Cardíaca:</dt><dd>{{ $latestVitalSign->heart_rate }} lpm</dd></div>
                    @endif
                    @if($latestVitalSign->systolic_pressure)
                        <div class="flex justify-between"><dt class="text-gray-500">Presión Arterial:</dt><dd>{{ $latestVitalSign->systolic_pressure }}/{{ $latestVitalSign->diastolic_pressure }} mmHg</dd></div>
                    @endif
                    @if($latestVitalSign->weight)
                        <div class="flex justify-between"><dt class="text-gray-500">Peso:</dt><dd>{{ $latestVitalSign->weight }} kg</dd></div>
                    @endif
                    @if($latestVitalSign->height)
                        <div class="flex justify-between"><dt class="text-gray-500">Altura:</dt><dd>{{ $latestVitalSign->height }} cm</dd></div>
                    @endif
                    @if($latestVitalSign->oxygen_saturation)
                        <div class="flex justify-between"><dt class="text-gray-500">Saturación O₂:</dt><dd>{{ $latestVitalSign->oxygen_saturation }}%</dd></div>
                    @endif
                    @if($latestVitalSign->bmi)
                        <div class="flex justify-between"><dt class="text-gray-500">IMC:</dt><dd>{{ $latestVitalSign->bmi }}</dd></div>
                    @endif
                </dl>
                <p class="text-xs text-gray-400 mt-3">Registrado: {{ $latestVitalSign->created_at->format('d/m/Y H:i') }}</p>
            @else
                <p class="text-gray-500 text-sm">No hay signos vitales registrados.</p>
            @endif
        </div>
    </div>

    <!-- Columna 2: Alergias + Condiciones Crónicas -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-semibold">Alergias</h2>
                @if(auth()->id() === $patient->id)
                    <button onclick="document.getElementById('add-allergy').classList.toggle('hidden')" class="text-blue-600 hover:underline text-sm">+ Agregar</button>
                @endif
            </div>

            @if(auth()->id() === $patient->id)
                <div id="add-allergy" class="hidden mb-4 bg-gray-50 p-4 rounded-lg">
                    <form method="POST" action="{{ route('patient.allergies.store', $patient) }}" class="space-y-3">
                        @csrf
                        <input type="text" name="allergen" placeholder="Alérgeno" required class="w-full border-gray-300 rounded-lg text-sm">
                        <select name="severity" class="w-full border-gray-300 rounded-lg text-sm">
                            <option value="mild">Leve</option>
                            <option value="moderate">Moderado</option>
                            <option value="severe">Severo</option>
                        </select>
                        <input type="text" name="reaction" placeholder="Reacción (opcional)" class="w-full border-gray-300 rounded-lg text-sm">
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Guardar</button>
                    </form>
                </div>
            @endif

            <div class="space-y-2">
                @forelse($patient->allergies->where('is_active', true) as $allergy)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium text-sm">{{ $allergy->allergen }}</span>
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                                {{ match($allergy->severity) { 'severe' => 'bg-red-100 text-red-800', 'moderate' => 'bg-orange-100 text-orange-800', default => 'bg-yellow-100 text-yellow-800' } }}">
                                {{ match($allergy->severity) { 'severe' => 'Severo', 'moderate' => 'Moderado', default => 'Leve' } }}
                            </span>
                            @if($allergy->reaction)<p class="text-xs text-gray-500 mt-1">{{ $allergy->reaction }}</p>@endif
                        </div>
                        @if(auth()->id() === $patient->id)
                            <form method="POST" action="{{ route('patient.allergies.destroy', [$patient, $allergy]) }}" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs">✕</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay alergias registradas.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-semibold">Condiciones Crónicas</h2>
                @if(auth()->id() === $patient->id)
                    <button onclick="document.getElementById('add-condition').classList.toggle('hidden')" class="text-blue-600 hover:underline text-sm">+ Agregar</button>
                @endif
            </div>

            @if(auth()->id() === $patient->id)
                <div id="add-condition" class="hidden mb-4 bg-gray-50 p-4 rounded-lg">
                    <form method="POST" action="{{ route('patient.conditions.store', $patient) }}" class="space-y-3">
                        @csrf
                        <input type="text" name="condition_name" placeholder="Condición" required class="w-full border-gray-300 rounded-lg text-sm">
                        <input type="text" name="diagnosis_date" placeholder="Fecha diagnóstico (opcional)" class="w-full border-gray-300 rounded-lg text-sm">
                        <input type="text" name="treatment" placeholder="Tratamiento (opcional)" class="w-full border-gray-300 rounded-lg text-sm">
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Guardar</button>
                    </form>
                </div>
            @endif

            <div class="space-y-2">
                @forelse($patient->chronicConditions->where('is_active', true) as $condition)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium text-sm">{{ $condition->condition_name }}</span>
                            @if($condition->diagnosis_date)<span class="text-xs text-gray-400 ml-2">{{ $condition->diagnosis_date }}</span>@endif
                            @if($condition->treatment)<p class="text-xs text-gray-500 mt-1">{{ $condition->treatment }}</p>@endif
                        </div>
                        @if(auth()->id() === $patient->id)
                            <form method="POST" action="{{ route('patient.conditions.destroy', [$patient, $condition]) }}" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs">✕</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay condiciones crónicas registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Columna 3: Citas Recientes + Resumen -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Resumen</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total Citas:</dt>
                    <dd class="font-bold">{{ $patient->appointmentsAsPatient->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Historial Médico:</dt>
                    <dd class="font-bold">{{ $patient->medicalRecords->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Alergias Activas:</dt>
                    <dd class="font-bold">{{ $patient->allergies->where('is_active', true)->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Condiciones Crónicas:</dt>
                    <dd class="font-bold">{{ $patient->chronicConditions->where('is_active', true)->count() }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Últimas Citas</h2>
            <div class="space-y-3">
                @forelse($patient->appointmentsAsPatient as $appointment)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium">Dr(a). {{ $appointment->doctor->name }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->specialty->name }}</p>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ match($appointment->status) { 'completed' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-red-100 text-red-800', 'scheduled', 'confirmed' => 'bg-blue-100 text-blue-800', default => 'bg-gray-100 text-gray-800' } }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay citas registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
