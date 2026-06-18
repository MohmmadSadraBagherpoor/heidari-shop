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
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
