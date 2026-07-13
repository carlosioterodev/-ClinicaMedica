<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_id', 'recorded_by',
        'temperature', 'heart_rate', 'systolic_pressure', 'diastolic_pressure',
        'weight', 'height', 'oxygen_saturation', 'notes',
    ];

    protected $casts = [
        'temperature' => 'decimal:1',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'oxygen_saturation' => 'decimal:1',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getBmiAttribute(): ?float
    {
        if (!$this->weight || !$this->height || $this->height == 0) {
            return null;
        }
        $heightM = $this->height / 100;
        return round($this->weight / ($heightM * $heightM), 1);
    }
}
