<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'product_id',
        'region',
        'source',
        'status',
        'message',
        'raw_meta',
    ];

    protected $casts = [
        'raw_meta' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
