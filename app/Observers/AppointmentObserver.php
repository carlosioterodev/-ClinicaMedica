<?php

namespace App\Observers;

use App\Models\Appointment;

class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        \Log::info("Nueva cita agendada: #{$appointment->id}", [
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'scheduled_at' => $appointment->scheduled_at,
        ]);
    }

    public function updated(Appointment $appointment): void
    {
        if ($appointment->isDirty('status')) {
            \Log::info("Cita #{$appointment->id} cambió estado", [
                'from' => $appointment->getOriginal('status'),
                'to' => $appointment->status,
            ]);
        }
    }
}
