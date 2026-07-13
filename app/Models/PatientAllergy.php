<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAllergy extends Model
{
    protected $fillable = [
        'patient_id', 'allergen', 'severity', 'reaction', 'notes', 'is_active',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
