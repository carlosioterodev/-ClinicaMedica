<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'medical_record_id', 'doctor_id', 'patient_id', 'medication_id',
        'medication_name', 'dosage', 'frequency', 'duration_days',
        'instructions', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
