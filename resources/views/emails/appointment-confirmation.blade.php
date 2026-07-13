<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f9fafb;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="background: #2563eb; color: white; padding: 20px;">
            <h2 style="margin: 0;">{{ $clinicName }}</h2>
            <p style="margin: 5px 0 0; opacity: 0.9;">Confirmación de Cita</p>
        </div>

        <div style="padding: 20px;">
            <p>Hola <strong>{{ $appointment->patient->name }}</strong>,</p>
            <p>Tu cita médica ha sido confirmada con los siguientes datos:</p>

            <div style="background: #eff6ff; border-left: 4px solid #2563eb; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; color: #6b7280; width: 120px;"><strong>Fecha:</strong></td>
                        <td style="padding: 4px 0;">{{ $appointment->scheduled_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #6b7280;"><strong>Hora:</strong></td>
                        <td style="padding: 4px 0;">{{ $appointment->scheduled_at->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #6b7280;"><strong>Médico:</strong></td>
                        <td style="padding: 4px 0;">Dr(a). {{ $appointment->doctor->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #6b7280;"><strong>Especialidad:</strong></td>
                        <td style="padding: 4px 0;">{{ $appointment->specialty->name }}</td>
                    </tr>
                </table>
            </div>

            @if($clinicAddress)
                <p style="color: #6b7280; font-size: 14px;"><strong>Ubicación:</strong> {{ $clinicAddress }}</p>
            @endif

            <p style="color: #6b7280; font-size: 14px;">Por favor llegue 15 minutos antes de su cita. Si necesita cancelar o reprogramar, hágalo con al menos 24 horas de anticipación.</p>

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p style="color: #9ca3af; font-size: 12px;">
                    {{ $clinicName }}@if($clinicPhone) — {{ $clinicPhone }}@endif
                </p>
            </div>
        </div>
    </div>
</body>
</html>
