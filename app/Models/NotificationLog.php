<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'kamar_id',
        'channel',
        'type',
        'target',
        'message',
        'status',
        'response',
        'sent_for_date',
        'sent_at',
    ];
}