<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable=[
        'user_id',
        'order_id',
        'payment_method',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
