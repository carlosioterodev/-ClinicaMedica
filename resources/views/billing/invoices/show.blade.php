@extends('layouts.app')

@section('title', 'Factura ' . $invoice->invoice_number)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold">{{ $invoice->invoice_number }}</h1>
        <span class="px-3 py-1 text-sm font-semibold rounded-full
            {{ match($invoice->status) {
                'paid' => 'bg-green-100 text-green-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                'overdue' => 'bg-red-100 text-red-800',
                'cancelled' => 'bg-gray-200 text-gray-700',
                default => 'bg-gray-100 text-gray-800',
            } }}">
            {{ match($invoice->status) { 'paid' => 'Pagada', 'pending' => 'Pendiente', 'overdue' => 'Vencida', 'cancelled' => 'Cancelada', default => ucfirst($invoice->status) } }}
        </span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('billing.invoices.pdf', $invoice) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
            Descargar PDF
        </a>
        <a href="{{ route('billing.invoices.index') }}" class="text-blue-600 hover:underline text-sm self-center">← Volver</a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Paciente</h2>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Nombre:</dt><dd>{{ $invoice->patient->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">DNI:</dt><dd>{{ $invoice->patient->profile->dni ?? 'N/A' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Email:</dt><dd>{{ $invoice->patient->email }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Teléfono:</dt><dd>{{ $invoice->patient->profile->phone ?? 'N/A' }}</dd></div>
        </dl>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Factura</h2>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Número:</dt><dd class="font-medium">{{ $invoice->invoice_number }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Fecha Emisión:</dt><dd>{{ $invoice->created_at->format('d/m/Y H:i') }}</dd></div>
            @if($invoice->doctor)
                <div class="flex justify-between"><dt class="text-gray-500">Médico:</dt><dd>Dr(a). {{ $invoice->doctor->name }}</dd></div>
            @endif
            @if($invoice->appointment)
                <div class="flex justify-between"><dt class="text-gray-500">Cita:</dt><dd>#{{ $invoice->appointment->id }}</dd></div>
            @endif
            @if($invoice->paid_at)
                <div class="flex justify-between"><dt class="text-gray-500">Pagado:</dt><dd class="text-green-600 font-medium">{{ $invoice->paid_at->format('d/m/Y H:i') }}</dd></div>
            @endif
            @if($invoice->payment_method)
                <div class="flex justify-between"><dt class="text-gray-500">Método:</dt><dd>{{ match($invoice->payment_method) { 'cash' => 'Efectivo', 'card' => 'Tarjeta', 'transfer' => 'Transferencia', 'insurance' => 'Seguro', default => $invoice->payment_method } }}</dd></div>
            @endif
        </dl>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Totales</h2>
        <dl class="space-y-3">
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Subtotal:</dt><dd>${{ number_format($invoice->subtotal, 2) }}</dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">IVA:</dt><dd>${{ number_format($invoice->tax_amount, 2) }}</dd></div>
            <div class="flex justify-between text-lg font-bold border-t pt-3"><dt>Total:</dt><dd class="text-blue-600">${{ number_format($invoice->total, 2) }}</dd></div>
        </dl>
    </div>
</div>

<!-- Conceptos -->
<div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Conceptos</h2>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">Descripción</th>
                <th class="px-4 py-2 text-center">Cant.</th>
                <th class="px-4 py-2 text-right">P. Unitario</th>
                <th class="px-4 py-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($invoice->items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->description }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-3 text-right font-medium">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Registrar pago -->
@if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Registrar Pago</h2>
        <form method="POST" action="{{ route('billing.invoices.pay', $invoice) }}">
            @csrf
            <div class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Método de Pago <span class="text-red-500">*</span></label>
                    <select name="payment_method" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                        <option value="transfer">Transferencia</option>
                        <option value="insurance">Seguro</option>
                    </select>
                </div>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition"
                        onclick="return confirm('¿Marcar esta factura como pagada?')">
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>
@endif
@endsection
