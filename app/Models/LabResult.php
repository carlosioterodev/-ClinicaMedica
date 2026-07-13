<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        'patient_id', 'doctor_id', 'medical_record_id', 'test_name',
        'result_value', 'reference_range', 'unit', 'is_normal',
        'notes', 'file_path', 'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'is_normal' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
