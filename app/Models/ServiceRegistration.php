<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRegistration extends Model
{
    protected $fillable = [
        'ticket_number',
        'customer_name',
        'email',
        'phone',
        'product_name',
        'serial_number',
        'purchase_date',
        'issue_description',
        'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];
}
