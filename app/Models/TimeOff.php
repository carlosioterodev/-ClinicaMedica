<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOff extends Model
{
    protected $fillable = [
        'doctor_id', 'date', 'start_time', 'end_time',
        'reason', 'status', 'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
