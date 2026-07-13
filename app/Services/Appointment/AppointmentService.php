<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use App\Models\User;
use App\Services\Appointment\Contracts\AppointmentServiceInterface;
use App\Services\Notification\WhatsAppService;
use App\Mail\AppointmentConfirmationMail;
use App\Mail\AppointmentCancellationMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentService implements AppointmentServiceInterface
{
    public function __construct(
        private WhatsAppService $whatsApp,
    ) {}

    public function getAvailableSlots(User $doctor, Carbon $date, int $specialtyId): array
    {
        $availability = app(AvailabilityService::class);
        return $availability->getAvailableSlots($doctor, $date);
    }

    public function create(array $data): Appointment
    {
        $dateTime = Carbon::parse($data['scheduled_at']);
        $duration = $data['duration_minutes'] ?? 30;

        if ($this->hasConflict(
            User::findOrFail($data['doctor_id']),
            $dateTime,
            $duration
        )) {
            throw new \App\Exceptions\AppointmentConflictException(
                'El médico no está disponible en el horario seleccionado.'
            );
        }

        return DB::transaction(function () use ($data) {
            $appointment = Appointment::create($data);

            $appointment->load('patient', 'doctor', 'specialty');

            $this->sendConfirmationNotification($appointment);
            $this->whatsApp->sendAppointmentConfirmation($appointment);

            return $appointment;
        });
    }

    public function cancel(Appointment $appointment, string $reason): Appointment
    {
        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        $appointment->load('patient', 'doctor', 'specialty');

        $this->sendCancellationNotification($appointment);
        $this->whatsApp->sendAppointmentCancellation($appointment);

        return $appointment->fresh();
    }

    public function updateStatus(Appointment $appointment, string $status): Appointment
    {
        $appointment->update(['status' => $status]);
        return $appointment->fresh();
    }

    public function hasConflict(User $doctor, Carbon $dateTime, int $durationMinutes, ?int $excludeId = null): bool
    {
        $newEnd = $dateTime->copy()->addMinutes($durationMinutes);

        $query = Appointment::where('doctor_id', $doctor->id)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where(function ($q) use ($dateTime, $newEnd) {
                $q->whereBetween('scheduled_at', [
                    $dateTime->copy()->subMinutes(29)->toDateTimeString(),
                    $newEnd->copy()->subSecond()->toDateTimeString(),
                ]);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    private function sendConfirmationNotification(Appointment $appointment): void
    {
        try {
            $email = $appointment->patient->email;
            if ($email) {
                Mail::to($email)->send(new AppointmentConfirmationMail($appointment));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send appointment confirmation email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendCancellationNotification(Appointment $appointment): void
    {
        try {
            $email = $appointment->patient->email;
            if ($email) {
                Mail::to($email)->send(new AppointmentCancellationMail($appointment));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send appointment cancellation email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
