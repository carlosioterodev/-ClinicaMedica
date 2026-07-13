<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'room_number', 'specialty_id', 'is_active'];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
