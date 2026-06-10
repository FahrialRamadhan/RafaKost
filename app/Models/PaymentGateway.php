<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
        'cashify_license_key',
        'cashify_qr_id',
        'tokopay_merchant_id',
        'tokopay_secret_key',
        'tokopay_channel',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}