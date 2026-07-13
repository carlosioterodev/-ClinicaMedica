<?php

namespace App\Livewire\Doctor;

use App\Models\Appointment;
use Livewire\Component;

class UpcomingAppointments extends Component
{
    public function render()
    {
        $appointments = Appointment::where('doctor_id', auth()->id())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('scheduled_at', '>=', now())
            ->with('patient', 'specialty')
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        return view('livewire.doctor.upcoming-appointments', compact('appointments'));
    }
}
