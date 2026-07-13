<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2 style="color: #2563eb;">{{ $clinic }}</h2>
    <p>Hola <strong>{{ $patient->name }}</strong>,</p>
    <p>Adjunto encontrará su factura <strong>{{ $invoice->invoice_number }}</strong>.</p>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="background: #f3f4f6;">
                <th style="padding: 8px; border: 1px solid #ddd;">Descripción</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Cant.</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Precio</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $item->description }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $item->quantity }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">${{ number_format($item->unit_price, 2) }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total: ${{ number_format($invoice->total, 2) }}</strong></p>
    <p style="color: #666; font-size: 12px;">Atentamente, {{ $clinic }}</p>
</body>
</html>
