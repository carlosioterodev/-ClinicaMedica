@extends('layouts.app')

@section('title', 'Crear Registro Médico - Cita #' . $appointment->id)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Nuevo Registro Médico</h1>
        <p class="text-gray-500 text-sm">Paciente: {{ $appointment->patient->name }} — Cita #{{ $appointment->id }}</p>
    </div>
    <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-blue-600 hover:underline">← Volver a la Cita</a>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('doctor.medical-records.store', $appointment) }}">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos Clínicos</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico <span class="text-red-500">*</span></label>
                <textarea name="diagnosis" rows="3" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe el diagnóstico...">{{ old('diagnosis') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Síntomas</label>
                <textarea name="symptoms" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Síntomas reportados...">{{ old('symptoms') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento</label>
                <textarea name="treatment" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Plan de tratamiento...">{{ old('treatment') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas Adicionales</label>
                <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Notas internas...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos del Paciente</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Nombre:</dt><dd>{{ $appointment->patient->name }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">DNI:</dt><dd>{{ $appointment->patient->profile->dni ?? 'N/A' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Edad:</dt><dd>{{ $appointment->patient->profile->age ?? 'N/A' }} años</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tipo Sangre:</dt><dd>{{ $appointment->patient->profile->blood_type ?? 'N/A' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Motivo de la Cita</h2>
                <p class="text-sm text-gray-600">{{ $appointment->reason ?: 'No especificado' }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            Guardar Registro
        </button>
        <a href="{{ route('doctor.appointments.show', $appointment) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
            Cancelar
        </a>
    </div>
</form>
@endsection
