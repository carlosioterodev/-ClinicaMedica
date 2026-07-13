<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_id', 'doctor_id', 'invoice_number',
        'subtotal', 'tax_amount', 'total', 'status',
        'paid_at', 'payment_method', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateNumber(): string
    {
        $year = now()->format('Y');
        $sequence = static::whereYear('created_at', now()->year)->count() + 1;
        return sprintf('FAC-%s-%05d', $year, $sequence);
    }
}
