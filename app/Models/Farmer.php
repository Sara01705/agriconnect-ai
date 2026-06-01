<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'district',
        'state',
        'password',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
       protected $casts = [
    'verified' => 'boolean',
];
}


