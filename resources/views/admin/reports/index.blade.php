@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<h1 class="text-2xl font-bold mb-6">Reportes</h1>

<!-- Filtros de fecha -->
<div class="bg-white p-4 rounded-lg shadow-sm border mb-6">
    <form method="GET" class="flex gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Fecha Desde</label>
            <input type="date" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}"
                   class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Fecha Hasta</label>
            <input type="date" name="date_to" value="{{ $dateTo->format('Y-m-d') }}"
                   class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filtrar</button>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">Hoy</a>
    </form>
</div>

<!-- KPIs -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Pacientes</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_patients'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Médicos</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['total_doctors'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Citas Hoy</p>
        <p class="text-2xl font-bold text-purple-600">{{ $stats['appointments_today'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Citas (Período)</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['appointments_month'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Ingresos (Período)</p>
        <p class="text-2xl font-bold text-green-600">${{ number_format($stats['revenue_month'], 0) }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Pendientes</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['pending_invoices'] }}</p>
    </div>
</div>

<!-- Gráficas -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Citas por Estado -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4">Citas por Estado</h2>
        <div class="space-y-3">
            @foreach($appointmentsByStatus as $status => $count)
                @php
                    $label = match($status) { 'scheduled' => 'Programadas', 'confirmed' => 'Confirmadas', 'in_progress' => 'En Progreso', 'completed' => 'Completadas', 'cancelled' => 'Canceladas', 'no_show' => 'No Asistió', default => $status };
                    $color = match($status) { 'completed' => 'bg-green-500', 'confirmed' => 'bg-blue-500', 'scheduled' => 'bg-gray-500', 'cancelled' => 'bg-red-500', 'in_progress' => 'bg-yellow-500', 'no_show' => 'bg-orange-500', default => 'bg-gray-400' };
                    $total = $appointmentsByStatus->sum();
                    $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium">{{ $label }}</span>
                        <span class="text-gray-500">{{ $count }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
            @if($appointmentsByStatus->isEmpty())
                <p class="text-gray-500 text-sm text-center">Sin datos en el período.</p>
            @endif
        </div>
    </div>

    <!-- Citas por Especialidad -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4">Citas por Especialidad</h2>
        <div class="space-y-3">
            @foreach($appointmentsBySpecialty as $specialty => $count)
                @php
                    $total = $appointmentsBySpecialty->sum();
                    $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium">{{ $specialty }}</span>
                        <span class="text-gray-500">{{ $count }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
            @if($appointmentsBySpecialty->isEmpty())
                <p class="text-gray-500 text-sm text-center">Sin datos en el período.</p>
            @endif
        </div>
    </div>

    <!-- Tendencia Mensual de Citas -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4">Tendencia Mensual de Citas</h2>
        <div class="space-y-2">
            @foreach($appointmentsByMonth as $month => $count)
                @php
                    $maxCount = $appointmentsByMonth->max();
                    $pct = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0;
                @endphp
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 w-16 text-right">{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-4">
                        <div class="bg-indigo-500 h-4 rounded-full flex items-center justify-end pr-2" style="width: {{ max($pct, 5) }}%">
                            <span class="text-xs text-white font-medium">{{ $count }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($appointmentsByMonth->isEmpty())
                <p class="text-gray-500 text-sm text-center">Sin datos de tendencia.</p>
            @endif
        </div>
    </div>

    <!-- Top Médicos -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4">Top Médicos (Consultas Completadas)</h2>
        <div class="space-y-3">
            @foreach($topDoctors as $doctor => $count)
                @php
                    $maxCount = $topDoctors->max();
                    $pct = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium">Dr(a). {{ $doctor }}</span>
                        <span class="text-gray-500">{{ $count }} citas</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
            @if($topDoctors->isEmpty())
                <p class="text-gray-500 text-sm text-center">Sin datos en el período.</p>
            @endif
        </div>
    </div>
</div>

<!-- Ingresos Mensuales -->
<div class="bg-white p-6 rounded-lg shadow-sm border mb-8">
    <h2 class="text-lg font-semibold mb-4">Ingresos Mensuales</h2>
    <div class="space-y-2">
        @foreach($revenueByMonth as $month => $revenue)
            @php
                $maxRevenue = $revenueByMonth->max() ?: 1;
                $pct = round(($revenue / $maxRevenue) * 100);
            @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 w-16 text-right">{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</span>
                <div class="flex-1 bg-gray-200 rounded-full h-6">
                    <div class="bg-green-500 h-6 rounded-full flex items-center justify-end pr-2" style="width: {{ max($pct, 8) }}%">
                        <span class="text-xs text-white font-medium">${{ number_format($revenue, 0) }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        @if($revenueByMonth->isEmpty())
            <p class="text-gray-500 text-sm text-center">Sin datos de ingresos.</p>
        @endif
    </div>
</div>

<!-- Últimas Facturas -->
<div class="bg-white p-6 rounded-lg shadow-sm border">
    <h2 class="text-lg font-semibold mb-4">Últimas Facturas (30 días)</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentInvoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-2">{{ $invoice->patient->name }}</td>
                        <td class="px-4 py-2 font-medium">${{ number_format($invoice->total, 2) }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 inline-flex text-xs rounded-full font-medium
                                {{ match($invoice->status) { 'paid' => 'bg-green-100 text-green-800', 'pending' => 'bg-yellow-100 text-yellow-800', default => 'bg-gray-100 text-gray-800' } }}">
                                {{ match($invoice->status) { 'paid' => 'Pagada', 'pending' => 'Pendiente', default => ucfirst($invoice->status) } }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">Sin facturas recientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
