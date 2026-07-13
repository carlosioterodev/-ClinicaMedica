@extends('layouts.app')

@section('title', 'Dashboard Paciente')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mi Panel</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-sm font-medium text-gray-500">Próximas Citas</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['upcoming_appointments'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-sm font-medium text-gray-500">Consultas Realizadas</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['completed_appointments'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-sm font-medium text-gray-500">Facturas Pendientes</h3>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_invoices'] }}</p>
    </div>
</div>

@if($nextAppointment)
    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg mb-6">
        <h2 class="text-lg font-semibold text-blue-800 mb-2">Proxima Cita</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-900 font-medium">Dr(a). {{ $nextAppointment->doctor->name }}</p>
                <p class="text-blue-700 text-sm">{{ $nextAppointment->specialty->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-blue-900 font-bold text-lg">{{ $nextAppointment->scheduled_at->format('d/m/Y') }}</p>
                <p class="text-blue-700 text-sm">{{ $nextAppointment->scheduled_at->format('H:i') }} hrs</p>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('patient.appointments.create') }}" class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-1">Agendar Cita</h3>
        <p class="text-gray-500 text-sm">Reserva una nueva cita medica.</p>
    </a>
    <a href="{{ route('patient.appointments.index') }}" class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-1">Mis Citas</h3>
        <p class="text-gray-500 text-sm">Consulta y gestiona tus citas.</p>
    </a>
    <a href="{{ route('patient.show', auth()->id()) }}" class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-1">Mi Perfil</h3>
        <p class="text-gray-500 text-sm">Edita tus datos y revisa tu historial.</p>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Ultimas Citas</h2>
        <div class="space-y-3">
            @forelse($recentAppointments as $apt)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium">Dr(a). {{ $apt->doctor->name }}</p>
                        <p class="text-xs text-gray-500">{{ $apt->specialty->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ $apt->scheduled_at->format('d/m/Y H:i') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ match($apt->status) { 'completed' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-red-100 text-red-800', default => 'bg-blue-100 text-blue-800' } }}">
                            {{ match($apt->status) { 'scheduled' => 'Programada', 'confirmed' => 'Confirmada', 'completed' => 'Completada', 'cancelled' => 'Cancelada', default => ucfirst($apt->status) } }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">No hay citas recientes.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Facturas Pendientes</h2>
        <div class="space-y-3">
            @forelse($pendingInvoices as $invoice)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-yellow-600">${{ number_format($invoice->total, 2) }}</p>
                        <a href="{{ route('billing.invoices.show', $invoice) }}" class="text-xs text-blue-600 hover:underline">Ver</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">No hay facturas pendientes.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
