<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
    'farmer_id',
    'product_name',
    'category',
    'description',
    'price',
    'quantity',
    'unit',
    'status',
    'image',
    'admin_blocked'   // ✅ ADD THIS
];

    public function buyRequests()
{
    return $this->hasMany(\App\Models\BuyRequest::class);
}


    public function farmer()
{
    return $this->belongsTo(Farmer::class, 'farmer_id');
}
    public function requests()
{
    return $this->hasMany(BuyRequest::class);
}

    
}
