<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientChronicCondition extends Model
{
    protected $fillable = [
        'patient_id', 'condition_name', 'diagnosis_date', 'treatment', 'notes', 'is_active',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
