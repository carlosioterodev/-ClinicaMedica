<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id',
        'diagnosis', 'symptoms', 'treatment', 'notes', 'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }
}
