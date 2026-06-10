<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $fillable = [
        'nama',
        'lantai',
        'kamar_mandi',
        'harga',
        'image',
        'status',
		'images',
		'harga_1_orang',
		'harga_2_orang',
		'description',
	
    ];

    protected $casts = [
        'harga' => 'integer',
    ];
}