@extends('layouts.app')

@section('title', 'Panel Admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Panel de Administración</h1>

<!-- KPIs -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded-lg shadow-sm border">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-blue-600 font-bold text-sm">P</span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Pacientes</p>
                <p class="text-xl font-bold text-blue-600">{{ $stats['total_patients'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <span class="text-green-600 font-bold text-sm">M</span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Médicos</p>
                <p class="text-xl font-bold text-green-600">{{ $stats['total_doctors'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                <span class="text-yellow-600 font-bold text-sm">C</span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Citas Hoy</p>
                <p class="text-xl font-bold text-yellow-600">{{ $stats['appointments_today'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                <span class="text-purple-600 font-bold text-sm">$</span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Ingresos Mes</p>
                <p class="text-xl font-bold text-purple-600">${{ number_format($stats['revenue_month'], 0) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Citas de hoy -->
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Citas de Hoy</h2>
            <a href="{{ route('admin.appointments.index', ['date' => now()->format('Y-m-d')]) }}" class="text-blue-600 hover:underline text-sm">Ver todas</a>
        </div>
        @if($todayAppointments->count())
            <div class="space-y-2">
                @foreach($todayAppointments as $apt)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg text-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 font-mono w-12">{{ $apt->scheduled_at->format('H:i') }}</span>
                            <div>
                                <p class="font-medium">{{ $apt->patient->name }}</p>
                                <p class="text-xs text-gray-500">Dr(a). {{ $apt->doctor->name }} — {{ $apt->specialty->name }}</p>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ match($apt->status) { 'completed' => 'bg-green-100 text-green-800', 'confirmed' => 'bg-blue-100 text-blue-800', 'in_progress' => 'bg-yellow-100 text-yellow-800', default => 'bg-gray-100 text-gray-800' } }}">
                            {{ match($apt->status) { 'scheduled' => 'Programada', 'confirmed' => 'Confirmada', 'in_progress' => 'En Progreso', 'completed' => 'Completada', default => $apt->status } }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm text-center py-4">No hay citas programadas para hoy.</p>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Accesos rápidos -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Accesos Rápidos</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.users.create') }}" class="block p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition text-sm">
                    Crear Usuario
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition text-sm">
                    Ver Todas las Citas
                </a>
                <a href="{{ route('billing.invoices.create') }}" class="block p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition text-sm">
                    Crear Factura
                </a>
                <a href="{{ route('admin.reports.index') }}" class="block p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition text-sm">
                    Ver Reportes
                </a>
            </div>
        </div>

        <!-- Últimos usuarios -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Últimos Usuarios</h2>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                        @foreach($user->roles as $role)
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ match($role->name) { 'admin' => 'bg-purple-100 text-purple-800', 'doctor' => 'bg-blue-100 text-blue-800', 'nurse' => 'bg-green-100 text-green-800', 'patient' => 'bg-gray-100 text-gray-600', default => 'bg-gray-100 text-gray-800' } }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Sin usuarios recientes.</p>
                @endforelse
            </div>
        </div>

        <!-- Últimas facturas -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Últimas Facturas</h2>
            <div class="space-y-3">
                @forelse($recentInvoices as $invoice)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium">{{ $invoice->invoice_number }}</p>
                            <p class="text-xs text-gray-400">{{ $invoice->patient->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">${{ number_format($invoice->total, 2) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ match($invoice->status) { 'paid' => 'bg-green-100 text-green-800', 'pending' => 'bg-yellow-100 text-yellow-800', default => 'bg-gray-100 text-gray-800' } }}">
                                {{ match($invoice->status) { 'paid' => 'Pagada', 'pending' => 'Pendiente', default => ucfirst($invoice->status) } }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Sin facturas recientes.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
