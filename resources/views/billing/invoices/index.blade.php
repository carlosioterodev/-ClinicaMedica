@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Facturación</h1>
    <a href="{{ route('billing.invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
        + Nueva Factura
    </a>
</div>

<!-- Filtros -->
<div class="bg-white p-4 rounded-lg shadow-sm border mb-6">
    <form method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por número o paciente..."
               class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        <select name="status" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todos los estados</option>
            @foreach(['pending' => 'Pendiente', 'paid' => 'Pagada', 'overdue' => 'Vencida', 'cancelled' => 'Cancelada'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Desde"
               class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filtrar</button>
            <a href="{{ route('billing.invoices.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">Limpiar</a>
        </div>
    </form>
</div>

<!-- Resumen -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $pending = \App\Models\Invoice::where('status', 'pending')->sum('total');
        $paid = \App\Models\Invoice::where('status', 'paid')->sum('total');
        $count = \App\Models\Invoice::count();
    @endphp
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Total Facturas</p>
        <p class="text-2xl font-bold text-gray-800">{{ $count }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Pendientes</p>
        <p class="text-2xl font-bold text-yellow-600">${{ number_format($pending, 2) }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Cobradas</p>
        <p class="text-2xl font-bold text-green-600">${{ number_format($paid, 2) }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
        <p class="text-xs text-gray-500">Por Cobrar</p>
        <p class="text-2xl font-bold text-red-600">${{ number_format($pending, 2) }}</p>
    </div>
</div>

<!-- Tabla -->
<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médico</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3">{{ $invoice->patient->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $invoice->doctor ? 'Dr(a). ' . $invoice->doctor->name : '-' }}</td>
                    <td class="px-4 py-3 font-medium">${{ number_format($invoice->total, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 inline-flex text-xs rounded-full font-medium
                            {{ match($invoice->status) {
                                'paid' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'overdue' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-gray-200 text-gray-700',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ match($invoice->status) { 'paid' => 'Pagada', 'pending' => 'Pendiente', 'overdue' => 'Vencida', 'cancelled' => 'Cancelada', default => ucfirst($invoice->status) } }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('billing.invoices.show', $invoice) }}" class="text-blue-600 hover:underline">Ver</a>
                        <a href="{{ route('billing.invoices.pdf', $invoice) }}" class="text-green-600 hover:underline">PDF</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-4 text-center text-gray-500">No hay facturas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $invoices->links() }}</div>
</div>
@endsection
