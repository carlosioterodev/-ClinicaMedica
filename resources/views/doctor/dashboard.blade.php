@extends('layouts.app')

@section('title', 'Dashboard Doctor')

@section('content')
<h1 class="text-2xl font-bold mb-6">Panel del Doctor</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-sm font-medium text-gray-500">Citas Hoy</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">
            {{ \App\Models\Appointment::where('doctor_id', auth()->id())->whereDate('scheduled_at', today())->count() }}
        </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-sm font-medium text-gray-500">Atendidas (mes)</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">
            {{ \App\Models\Appointment::where('doctor_id', auth()->id())->where('status', 'completed')->whereMonth('created_at', now()->month)->count() }}
        </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-sm font-medium text-gray-500">Pendientes Hoy</h3>
        <p class="text-3xl font-bold text-yellow-600 mt-2">
            {{ \App\Models\Appointment::where('doctor_id', auth()->id())->whereIn('status', ['scheduled', 'confirmed'])->whereDate('scheduled_at', today())->count() }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h2 class="text-lg font-semibold mb-4">Próximas Citas</h2>
        <livewire:doctor.upcoming-appointments />
    </div>
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h2 class="text-lg font-semibold mb-4">Accesos Rápidos</h2>
        <div class="space-y-3">
            <a href="{{ route('doctor.appointments.index') }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                Ver todas las citas
            </a>
            <a href="{{ route('doctor.schedule.index') }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                Gestionar horarios
            </a>
            <a href="{{ route('doctor.time-off.index') }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                Registrar tiempo libre
            </a>
        </div>
    </div>
</div>
@endsection
