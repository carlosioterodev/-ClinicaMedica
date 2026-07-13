<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f9fafb;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="background: #dc2626; color: white; padding: 20px;">
            <h2 style="margin: 0;">{{ $clinicName }}</h2>
            <p style="margin: 5px 0 0; opacity: 0.9;">Cancelación de Cita</p>
        </div>

        <div style="padding: 20px;">
            <p>Hola <strong>{{ $appointment->patient->name }}</strong>,</p>
            <p>Tu cita programada para el <strong>{{ $appointment->scheduled_at->format('d/m/Y a\s H:i') }}</strong> ha sido cancelada.</p>

            @if($appointment->cancellation_reason)
                <div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; margin: 15px 0; border-radius: 4px;">
                    <p style="margin: 0; color: #991b1b;"><strong>Motivo:</strong> {{ $appointment->cancellation_reason }}</p>
                </div>
            @endif

            <p style="color: #6b7280; font-size: 14px;">Si desea reprogramar, puede agendar una nueva cita a través de nuestro sistema o contactarnos directamente.</p>

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p style="color: #9ca3af; font-size: 12px;">
                    {{ $clinicName }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
