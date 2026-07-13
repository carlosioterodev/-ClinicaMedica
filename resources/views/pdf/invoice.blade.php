<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .clinic-name { font-size: 20px; font-weight: bold; color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #2563eb; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .totals { text-align: right; margin-top: 20px; }
        .totals td { border: none; padding: 4px 8px; }
        .footer { margin-top: 40px; font-size: 10px; color: #666; text-align: center; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="clinic-name">{{ $clinic['name'] }}</div>
            <div>{{ $clinic['address'] }}</div>
            <div>{{ $clinic['phone'] }} | {{ $clinic['email'] }}</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 16px; font-weight: bold;">FACTURA</div>
            <div><strong>No:</strong> {{ $invoice->invoice_number }}</div>
            <div><strong>Fecha:</strong> {{ $invoice->created_at->format('d/m/Y') }}</div>
            <div><strong>Estado:</strong> {{ ucfirst($invoice->status) }}</div>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <h3>Datos del Paciente</h3>
        <p><strong>Nombre:</strong> {{ $invoice->patient->name }}</p>
        <p><strong>DNI:</strong> {{ $invoice->patient->profile->dni ?? 'N/A' }}</p>
        <p><strong>Teléfono:</strong> {{ $invoice->patient->profile->phone ?? 'N/A' }}</p>
        @if($invoice->doctor)
            <p><strong>Médico:</strong> Dr(a). {{ $invoice->doctor->name }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th style="text-align: center;">Cant.</th>
                <th style="text-align: right;">P. Unitario</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table style="width: 300px; margin-left: auto;">
            <tr><td><strong>Subtotal:</strong></td><td style="text-align: right;">${{ number_format($invoice->subtotal, 2) }}</td></tr>
            <tr><td><strong>IVA:</strong></td><td style="text-align: right;">${{ number_format($invoice->tax_amount, 2) }}</td></tr>
            <tr style="font-size: 14px;"><td><strong>TOTAL:</strong></td><td style="text-align: right;"><strong>${{ number_format($invoice->total, 2) }}</strong></td></tr>
        </table>
    </div>

    @if($invoice->paid_at)
        <div style="text-align: center; margin-top: 20px; color: #16a34a; font-weight: bold;">
            PAGADO - {{ $invoice->paid_at->format('d/m/Y H:i') }} ({{ ucfirst($invoice->payment_method) }})
        </div>
    @endif

    <div class="footer">
        <p>{{ $clinic['name'] }} - {{ $clinic['address'] }}</p>
        <p>Este documento es una representación impresa de la factura.</p>
    </div>
</body>
</html>
