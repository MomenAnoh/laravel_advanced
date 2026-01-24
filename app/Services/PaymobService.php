<?php

namespace App\Services;

use App\Models\Order;
use Beste\Json;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class PaymobService
{
    protected $header = [];
    protected $visa_integration_id;
    protected $wallet_integration_id;
    protected $currency = 'EGP';
    protected $api_key;
    protected $base_url ;
    protected $paymob_public_key ;

    public function __construct()
    {
        $this->base_url = config('services.paymob.base_url');
        $this->api_key = config('services.paymob.api_key');
        $this->wallet_integration_id = config('services.paymob.wallet_integration_id');
        $this->visa_integration_id = config('services.paymob.visa_integration_id');
        $this->paymob_public_key = config('services.paymob.public_key');
    }

    public function sendPayment(array $paymentData,$payment_method)
    {
        $this->header['Authorization'] = 'Token ' . env('PAYMOB_API_SECRET_KEY');
        $paymentData['currency'] = $this->currency;
        if($payment_method == 'visa')
        {
            $paymentData['payment_methods'] =[$this->visa_integration_id];
            $paymentData['integration_id'] = $this->visa_integration_id;
        }
        if($payment_method == 'wallet')
        {
            $paymentData['payment_methods'] = [$this->wallet_integration_id];
            $paymentData['integration_id'] = $this->wallet_integration_id;

        }

        $response = $this->buildRequest('POST', '/v1/intention/', $paymentData);
        $response=Json_decode($response);
          
          $unique_id=uniqid();
        if(isset($response->client_secret))
            {
            return [
                'payment_id'=>$unique_id,
                'html'=>"",
                'redirect_url'=>"https://accept.paymob.com/unifiedcheckout/?publicKey=".$this->paymob_public_key."&clientSecret=".$response->client_secret
            ];
        }
        return [
            'payment_id'=>$unique_id,
            'html'=>$response,
            'redirect_url'=>""

        ];
    }

    protected function buildRequest(string $method, string $url, array $data = null, string $type = 'json')
    {
        try {
            $response = Http::withHeaders($this->header)->send($method,$this->base_url .  $url, [
                $type => $data
            ]);
            return $response;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
