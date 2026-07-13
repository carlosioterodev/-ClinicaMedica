<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\TimeOff;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::where('doctor_id', auth()->id())
            ->orderBy('day_of_week')
            ->get();

        return view('doctor.schedule.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration_minutes' => 'required|integer|between:10,120',
        ]);

        $validated['doctor_id'] = auth()->id();

        DoctorSchedule::updateOrCreate(
            ['doctor_id' => $validated['doctor_id'], 'day_of_week' => $validated['day_of_week']],
            $validated
        );

        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Horario actualizado.');
    }

    public function destroy(DoctorSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Horario eliminado.');
    }

    public function timeOffIndex()
    {
        $timeOffs = TimeOff::where('doctor_id', auth()->id())->latest()->get();
        return view('doctor.schedule.time-off', compact('timeOffs'));
    }

    public function storeTimeOff(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:500',
        ]);

        $validated['doctor_id'] = auth()->id();
        TimeOff::create($validated);

        return redirect()->route('doctor.time-off.index')
            ->with('success', 'Tiempo libre registrado.');
    }
}
