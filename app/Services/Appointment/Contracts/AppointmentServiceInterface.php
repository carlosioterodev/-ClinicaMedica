<?php

namespace App\Services\Appointment\Contracts;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

interface AppointmentServiceInterface
{
    public function getAvailableSlots(User $doctor, Carbon $date, int $specialtyId): array;
    public function create(array $data): Appointment;
    public function cancel(Appointment $appointment, string $reason): Appointment;
    public function updateStatus(Appointment $appointment, string $status): Appointment;
    public function hasConflict(User $doctor, Carbon $dateTime, int $durationMinutes, ?int $excludeId = null): bool;
}
