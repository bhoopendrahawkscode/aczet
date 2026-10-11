<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dealer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'country',
        'address',
        'password',
        'status',
        'tier',
        'discount_percentage',
        'account_manager',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
    ];
}
