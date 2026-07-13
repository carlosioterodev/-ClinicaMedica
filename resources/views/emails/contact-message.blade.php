<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f9fafb;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="background: #2563eb; color: white; padding: 20px;">
            <h2 style="margin: 0;">{{ $clinicName }}</h2>
            <p style="margin: 5px 0 0; opacity: 0.9;">Nuevo mensaje de contacto</p>
        </div>

        <div style="padding: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; width: 120px; vertical-align: top;"><strong>Nombre:</strong></td>
                    <td style="padding: 8px 0;">{{ $contact->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; vertical-align: top;"><strong>Email:</strong></td>
                    <td style="padding: 8px 0;"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                </tr>
                @if($contact->phone)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; vertical-align: top;"><strong>Teléfono:</strong></td>
                    <td style="padding: 8px 0;">{{ $contact->phone }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; vertical-align: top;"><strong>Asunto:</strong></td>
                    <td style="padding: 8px 0;">{{ $contact->subject }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; vertical-align: top;"><strong>Mensaje:</strong></td>
                    <td style="padding: 8px 0; background: #f3f4f6; border-radius: 4px; padding: 12px;">{{ $contact->message }}</td>
                </tr>
            </table>

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p style="color: #9ca3af; font-size: 12px;">
                    Recibido el {{ $contact->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
