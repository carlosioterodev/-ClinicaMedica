<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentNote extends Model
{
    protected $fillable = [
        'appointment_id', 'author_id', 'note_type', 'content',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
