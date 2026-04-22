<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
   protected $fillable = [
    'nama',
    'lantai',
    'harga',
    'image',
    'status'
];
protected $casts = [
    'harga' => 'integer',
];
}

