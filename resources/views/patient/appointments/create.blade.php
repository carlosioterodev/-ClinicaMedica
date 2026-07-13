@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')
<h1 class="text-2xl font-bold mb-6">Agendar Nueva Cita</h1>

<div class="bg-white p-6 rounded-lg shadow-sm border max-w-2xl">
    <form method="POST" action="{{ route('patient.appointments.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
            <select name="specialty_id" id="specialty_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Seleccionar especialidad...</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Médico</label>
            <select name="doctor_id" id="doctor_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Seleccionar médico...</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
            <input type="date" name="scheduled_at" id="appointment_date" required min="{{ now()->addDay()->format('Y-m-d') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Horario Disponible</label>
            <select name="scheduled_at" id="time_slot" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Seleccionar fecha primero...</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo (opcional)</label>
            <textarea name="reason" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Describe brevemente el motivo de la consulta..."></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Agendar Cita
            </button>
            <a href="{{ route('patient.appointments.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('specialty_id')?.addEventListener('change', async function() {
    const specialtyId = this.value;
    const doctorSelect = document.getElementById('doctor_id');
    doctorSelect.innerHTML = '<option value="">Cargando médicos...</option>';

    if (!specialtyId) {
        doctorSelect.innerHTML = '<option value="">Seleccionar médico...</option>';
        return;
    }

    // Fetch doctors for this specialty (would need an API route)
    // For now, using a simple approach
    doctorSelect.innerHTML = '<option value="">Seleccionar médico...</option>';
});

document.getElementById('appointment_date')?.addEventListener('change', async function() {
    const date = this.value;
    const doctorId = document.getElementById('doctor_id').value;
    const specialtyId = document.getElementById('specialty_id').value;
    const timeSelect = document.getElementById('time_slot');

    if (!date || !doctorId || !specialtyId) {
        timeSelect.innerHTML = '<option value="">Seleccionar fecha y médico...</option>';
        return;
    }

    try {
        const response = await fetch(`/patient/appointments/slots?doctor_id=${doctorId}&date=${date}&specialty_id=${specialtyId}`);
        const slots = await response.json();

        timeSelect.innerHTML = '<option value="">Seleccionar horario...</option>';
        slots.forEach(slot => {
            if (slot.available) {
                const option = document.createElement('option');
                option.value = slot.datetime;
                option.textContent = `${slot.time} - ${slot.end_time}`;
                timeSelect.appendChild(option);
            }
        });
    } catch (e) {
        timeSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
    }
});
</script>
@endpush
@endsection
