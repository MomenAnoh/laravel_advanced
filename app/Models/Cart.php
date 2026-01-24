<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products')->withPivot('quantity');
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
