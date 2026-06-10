<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'pekerjaan',
        'photo',

        'identity_status',
        'ktp_photo',
        'selfie_photo',
		'selfie_ktp_photo',
        'identity_rejection_reason',
        'identity_submitted_at',
        'identity_verified_at',
        'identity_verified_by',

        'biometric_result',
		'notify_empty_room_email',
		'notify_empty_room_whatsapp',
		'api_token_hash',
		'api_token_created_at',
	
	    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

	protected function casts(): array
	{
	    return [
	        'email_verified_at' => 'datetime',
	        'password' => 'hashed',
	        'identity_submitted_at' => 'datetime',
	        'identity_verified_at' => 'datetime',
	        'biometric_checked_at' => 'datetime',
	        'notify_empty_room_email' => 'boolean',
	        'notify_empty_room_whatsapp' => 'boolean',
			'api_token_created_at' => 'datetime',
	    ];
	}
}