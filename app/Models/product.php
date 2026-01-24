<?php

namespace App\Models;

use App\Trait\DataFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    use DataFilter;
    protected $fillable=['name','des','image','price','quantity'];
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
