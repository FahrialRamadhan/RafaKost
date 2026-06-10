<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
	protected $fillable = [
	    'invoice',
	    'user_id',
	
	    'customer_name',
	    'customer_phone',
	    'customer_email',
	    'customer_address',
	    'customer_note',
	
	    'kamar_id',
	    'tanggal_masuk',
	    'durasi',
	    'orang',
	    'total_harga',
	    'payment_fee',
	    'payment_total',
	    'payment_gateway',
	    'payment_method_code',
	    'payment_method_name',
	    'payment_status',
	    'transaction_id',
	    'reference_id',
	    'qr_string',
	    'payment_url',
	    'paid_at',
	];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'paid_at' => 'datetime',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}