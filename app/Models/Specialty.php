<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function doctors()
    {
        return $this->belongsToMany(User::class, 'doctor_specialties', 'specialty_id', 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
