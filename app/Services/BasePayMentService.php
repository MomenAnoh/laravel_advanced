<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class BasePayMentService
{
    public function getData($total_price)
    {
        $unique_id=uniqid();
          return [
            'amount'=>10,
            'currency'=> "EGP",
            'billing_data'=>[
               "first_name"   => "Momen",
              "last_name"    => "Ahmed",
                'phone_number'=>"+201093373197",
                'email'=>Auth::user()->email,
            ],
            'special_reference'=>$unique_id,
            'expiration'=>3600,
            'redirection_url'=>route('payment.success'),
            'redirection_url'=>route('payment.success'),
        ];


    }
}
