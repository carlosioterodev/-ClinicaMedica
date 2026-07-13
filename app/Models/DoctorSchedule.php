<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id', 'day_of_week', 'start_time',
        'end_time', 'slot_duration_minutes', 'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
