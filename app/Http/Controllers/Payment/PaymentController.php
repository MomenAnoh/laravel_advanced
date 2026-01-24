<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\BasePayMentService;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    public $basePayMentService;
    public $paymobService;
    public function __construct(BasePayMentService $basePayMentService ,PaymobService $paymobService)
    {
        $this->basePayMentService=$basePayMentService;
        $this->paymobService=$paymobService;
    }

    public function payOrder($order_id,Request $request)
    {

        $payment_method=$request->payment_method;
        $total_price=0;
      $order=Order::with('cart.products')->find($order_id);
          if($order==null)
          {
            return 'order not found';
          }

       foreach($order->cart->products as $product)
       {
        $total_price+=$product->price*$product->pivot->quantity;
       }

        $payment_data=$this->basePayMentService->getData($total_price);
        $url = $this->paymobService->sendPayment($payment_data,$payment_method);
       $this->createPayment($order_id,$payment_method);
        return $url;

    }
    public function createPayment($order_id,$payment_method)
    {

        PaymentTransaction::create([
            'user_id'=>Auth::user()->id,
            'order_id'=>$order_id,
            'payment_method'=>$payment_method,
        ]);
    }

    public function successProcess(Request $request)
    {
        Log::info('suscess');
        return response()->json(['status' => 'ok']);
    }


    public function failPaymentCallback()
    {
        return view('fail');
    }
}
