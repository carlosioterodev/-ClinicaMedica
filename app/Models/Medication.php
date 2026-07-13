<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'name', 'generic_name', 'presentation', 'manufacturer',
        'stock', 'unit_price', 'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
