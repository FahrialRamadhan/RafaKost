<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'gateway_code',
        'name',
        'code',
        'category',
        'fee_fixed',
        'fee_percent',
        'info',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee_percent' => 'decimal:2',
    ];
}