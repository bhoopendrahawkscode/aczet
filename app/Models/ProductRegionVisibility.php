<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRegionVisibility extends Model
{
    protected $fillable = ['product_id', 'region', 'is_visible'];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
