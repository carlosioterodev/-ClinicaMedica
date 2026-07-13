@extends('layouts.app')

@section('title', 'Mis Facturas')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mis Facturas</h1>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médico</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse(auth()->user()->invoices()->with('doctor')->latest()->paginate(15) as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $invoice->doctor ? 'Dr(a). ' . $invoice->doctor->name : '-' }}</td>
                    <td class="px-4 py-3 font-medium">${{ number_format($invoice->total, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 inline-flex text-xs rounded-full font-medium
                            {{ match($invoice->status) {
                                'paid' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'overdue' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ match($invoice->status) { 'paid' => 'Pagada', 'pending' => 'Pendiente', 'overdue' => 'Vencida', default => ucfirst($invoice->status) } }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('billing.invoices.show', $invoice) }}" class="text-blue-600 hover:underline">Ver</a>
                        <a href="{{ route('billing.invoices.pdf', $invoice) }}" class="text-green-600 hover:underline">PDF</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No tienes facturas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
