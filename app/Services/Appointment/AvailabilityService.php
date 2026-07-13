<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\TimeOff;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityService
{
    public function __construct(
        private DoctorSchedule $scheduleModel,
        private TimeOff $timeOffModel,
        private Appointment $appointmentModel,
    ) {}

    public function getAvailableSlots(User $doctor, Carbon $date, int $durationMinutes = 30): array
    {
        $dayOfWeek = $date->dayOfWeekIso;

        $schedule = $this->scheduleModel
            ->where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return [];
        }

        if ($this->isDoctorOnTimeOff($doctor->id, $date, $schedule)) {
            return [];
        }

        $booked = $this->getBookedSlots($doctor->id, $date);

        return $this->buildSlots($schedule, $date, $booked, $durationMinutes);
    }

    public function getDoctorsBySpecialty(int $specialtyId): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereHas('specialties', fn ($q) => $q->where('specialties.id', $specialtyId))
            ->whereHas('roles', fn ($q) => $q->where('name', 'doctor'))
            ->with('profile')
            ->get();
    }

    private function isDoctorOnTimeOff(int $doctorId, Carbon $date, DoctorSchedule $schedule): bool
    {
        $dayOff = $this->timeOffModel
            ->where('doctor_id', $doctorId)
            ->where('date', $date->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if (!$dayOff) {
            return false;
        }

        if (!$dayOff->start_time && !$dayOff->end_time) {
            return true;
        }

        $offStart = Carbon::parse($dayOff->start_time);
        $offEnd = Carbon::parse($dayOff->end_time);
        $schStart = Carbon::parse($schedule->start_time);
        $schEnd = Carbon::parse($schedule->end_time);

        return $offStart->lte($schStart) && $offEnd->gte($schEnd);
    }

    private function getBookedSlots(int $doctorId, Carbon $date): \Illuminate\Support\Collection
    {
        return $this->appointmentModel
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $date->toDateString())
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->get()
            ->map(fn ($a) => [
                'start' => Carbon::parse($a->scheduled_at),
                'end' => Carbon::parse($a->scheduled_at)->addMinutes($a->duration_minutes),
            ]);
    }

    private function buildSlots(
        DoctorSchedule $schedule,
        Carbon $date,
        \Illuminate\Support\Collection $booked,
        int $durationMinutes
    ): array {
        $slots = [];
        $current = $date->copy()->setTimeFromTimeString($schedule->start_time);
        $dayEnd = $date->copy()->setTimeFromTimeString($schedule->end_time);

        while ($current->copy()->addMinutes($durationMinutes)->lte($dayEnd)) {
            $slotEnd = $current->copy()->addMinutes($durationMinutes);

            $isBooked = $booked->contains(fn ($b) =>
                $current->lt($b['end']) && $slotEnd->gt($b['start'])
            );

            $slots[] = [
                'time' => $current->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'datetime' => $current->toDateTimeString(),
                'available' => !$isBooked,
            ];

            $current->addMinutes($durationMinutes);
        }

        return $slots;
    }
}
