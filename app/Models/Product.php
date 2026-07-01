<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'off_price',
        'name',
        'stock',
        'type',
        'description',
        'price',
        'image',
        'status',
        'discount_ends_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'discount_ends_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
