@extends('layouts.app')

@section('title', 'Nueva Factura')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Crear Nueva Factura</h1>
    <a href="{{ route('billing.invoices.index') }}" class="text-blue-600 hover:underline">← Volver</a>
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

<form method="POST" action="{{ route('billing.invoices.store') }}" x-data="invoiceForm()">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos de la factura -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-lg font-semibold mb-4">Datos de la Factura</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paciente <span class="text-red-500">*</span></label>
                        <select name="patient_id" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar paciente...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} — {{ $patient->profile->dni ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Médico</label>
                        <select name="doctor_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar médico...</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr(a). {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cita Asociada</label>
                        <select name="appointment_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Ninguna</option>
                            @foreach($appointments as $apt)
                                <option value="{{ $apt->id }}" {{ old('appointment_id') == $apt->id ? 'selected' : '' }}>
                                    #{{ $apt->id }} — {{ $apt->patient->name }} — {{ $apt->scheduled_at->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tasa de Impuesto</label>
                        <select name="tax_rate" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="0" {{ old('tax_rate', '0.16') === '0' ? 'selected' : '' }}>0%</option>
                            <option value="0.16" {{ old('tax_rate', '0.16') === '0.16' ? 'selected' : '' }}>16% (IVA)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Conceptos -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Conceptos</h2>
                    <button type="button" @click="addItem()" class="text-blue-600 hover:underline text-sm">+ Agregar concepto</button>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="flex gap-3 mb-3 items-end">
                        <div class="flex-1">
                            <input type="text" :name="'items[' + index + '][description]'" x-model="item.description"
                                   placeholder="Descripción" required class="w-full border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="w-20">
                            <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity"
                                   placeholder="Cant." min="1" required class="w-full border-gray-300 rounded-lg text-sm"
                                   @input="calculateTotal()">
                        </div>
                        <div class="w-28">
                            <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price"
                                   placeholder="P. Unitario" min="0" step="0.01" required class="w-full border-gray-300 rounded-lg text-sm"
                                   @input="calculateTotal()">
                        </div>
                        <div class="w-28 text-sm font-medium text-right" x-text="'$' + (item.quantity * item.unit_price).toFixed(2)"></div>
                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 text-sm px-2">✕</button>
                    </div>
                </template>

                <div x-show="items.length === 0" class="text-center text-gray-500 text-sm py-4">
                    No hay conceptos. Haz clic en "+ Agregar concepto" para comenzar.
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white p-6 rounded-lg shadow-sm border sticky top-6">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Resumen</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal:</span>
                        <span class="font-medium" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">IVA (16%):</span>
                        <span class="font-medium" x-text="'$' + taxAmount.toFixed(2)"></span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold">Total:</span>
                        <span class="font-bold text-lg text-blue-600" x-text="'$' + total.toFixed(2)"></span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 mt-6 font-semibold"
                        :disabled="items.length === 0">
                    Generar Factura
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function invoiceForm() {
    return {
        items: [{ description: '', quantity: 1, unit_price: 0 }],
        subtotal: 0,
        taxAmount: 0,
        total: 0,
        addItem() {
            this.items.push({ description: '', quantity: 1, unit_price: 0 });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.calculateTotal();
        },
        calculateTotal() {
            this.subtotal = this.items.reduce((sum, item) => sum + (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0), 0);
            this.taxAmount = this.subtotal * 0.16;
            this.total = this.subtotal + this.taxAmount;
        }
    }
}
</script>
@endpush
@endsection
