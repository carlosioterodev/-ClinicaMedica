<?php

namespace App\Services\Notification;

use App\Models\Appointment;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiUrl;
    private string $phoneNumberId;
    private string $accessToken;

    public function __construct()
    {
        $this->apiUrl = config('clinic.whatsapp.api_url', '');
        $this->phoneNumberId = config('clinic.whatsapp.phone_number_id', '');
        $this->accessToken = config('clinic.whatsapp.access_token', '');
    }

    public function sendAppointmentConfirmation(Appointment $appointment): void
    {
        if (!config('clinic.whatsapp.enabled')) {
            return;
        }

        $phone = $appointment->patient->profile->phone ?? null;
        if (!$phone) {
            return;
        }

        $message = "Hola {$appointment->patient->name}, "
            . "su cita ha sido programada para el "
            . "{$appointment->scheduled_at->format('d/m/Y \\a\\s H:i')} "
            . "con Dr(a). {$appointment->doctor->name}.";

        $this->send($phone, $message, $appointment->patient_id);
    }

    public function sendAppointmentCancellation(Appointment $appointment): void
    {
        if (!config('clinic.whatsapp.enabled')) {
            return;
        }

        $phone = $appointment->patient->profile->phone ?? null;
        if (!$phone) {
            return;
        }

        $message = "Hola {$appointment->patient->name}, "
            . "su cita del {$appointment->scheduled_at->format('d/m/Y \\a\\s H:i')} "
            . "ha sido cancelada.";

        $this->send($phone, $message, $appointment->patient_id);
    }

    private function send(string $phone, string $message, ?int $userId = null): void
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhone($phone),
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);

            NotificationLog::create([
                'user_id' => $userId,
                'channel' => 'whatsapp',
                'body' => $message,
                'sent_at' => now(),
                'status' => $response->successful() ? 'sent' : 'failed',
                'metadata' => $response->json(),
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', ['error' => $e->getMessage()]);
        }
    }

    private function formatPhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
