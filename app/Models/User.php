<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialties', 'doctor_id', 'specialty_id');
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class, 'patient_id');
    }

    public function allergies()
    {
        return $this->hasMany(PatientAllergy::class, 'patient_id');
    }

    public function chronicConditions()
    {
        return $this->hasMany(PatientChronicCondition::class, 'patient_id');
    }
}
