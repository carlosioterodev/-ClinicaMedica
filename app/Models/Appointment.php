<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id', 'doctor_id', 'specialty_id', 'scheduled_at',
        'duration_minutes', 'status', 'reason', 'cancellation_reason', 'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function notes()
    {
        return $this->hasMany(AppointmentNote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
