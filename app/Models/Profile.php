<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'dni', 'phone', 'date_of_birth',
        'gender', 'address', 'blood_type',
        'emergency_contact_name', 'emergency_contact_phone', 'avatar',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }
}
