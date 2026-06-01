<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyRequest extends Model
{
    protected $fillable = [
    'product_id',
    'user_id',
    'quantity',
    'total_price',
    'status'
];

    // USER relationship (NOT buyer)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Product relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}