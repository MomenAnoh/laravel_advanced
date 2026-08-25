<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TamaraController extends Controller
{
    public function tamar(Request $request)
    {
      
        $amount = 10;
        $currency = 'SAR';
        $countryCode = 'SA';

        $firstName =  'Customer';
        $lastName = 'Customer';
        $email = 'customer@example.com';
        $phone = '966500000000';

        $referenceId = 12345;

        $requestBody = [
            'total_amount' => ['amount' => $amount, 'currency' => $currency],
            'shipping_amount' => ['amount' => 0, 'currency' => $currency],
            'tax_amount' => ['amount' => 0, 'currency' => $currency],
            'order_reference_id' => 12345,
            'order_number' => 'ORD-' . time(),
            'items' => [
                [
                    'name' => 'Order Payment',
                    'type' => 'Digital',
                    'reference_id' => $request->input('item_reference_id', '1'),
                    'sku' => 'PAYMENT-001',
                    'quantity' => 1,
                    'unit_price' => ['amount' => $amount, 'currency' => $currency],
                    'total_amount' => ['amount' => $amount, 'currency' => $currency],
                ],
            ],
            'consumer' => [
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $phone,
            ],
            'country_code' => $countryCode,
            'description' => $request->input('description', 'Payment for order'),
            'merchant_url' => [
                'cancel' => route('tamara.cancel', ['reference_id' => $referenceId]),
                'failure' => route('tamara.failure', ['reference_id' => $referenceId]),
                'success' => route('tamara.callback', ['reference_id' => $referenceId]),
                'notification' => route('tamara.webhook'),
            ],
            'payment_type' => config('tamara.payment_type', 'PAY_BY_INSTALMENTS'),
            'instalments' => (int) config('tamara.instalments', 3),
            'billing_address' => [
                'city' => $request->input('city', 'Riyadh'),
                'country_code' => $countryCode,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'line1' => $request->input('address_line1', 'Default Address'),
                'phone_number' => $phone,
            ],
            'shipping_address' => [
                'city' => $request->input('city', 'Riyadh'),
                'country_code' => $countryCode,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'line1' => $request->input('address_line1', 'Default Address'),
                'phone_number' => $phone,
            ],
            'platform' => config('app.name', 'Laravel'),
            'is_mobile' => $request->input('is_mobile', false),
            'locale' => config('tamara.locale', 'en_US'),
        ];


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('TAMARA_MERCHANT_KEY'),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post(env('TAMARA_API_URL') . '/checkout', $requestBody);

        return $response->json();
    }

    public function cancel($reference_id)
    {
        return response()->json(['status' => 'cancelled', 'reference_id' => $reference_id]);
    }

    public function failure($reference_id)
    {
        return response()->json(['status' => 'failed', 'reference_id' => $reference_id]);
    }

    public function callback($reference_id)
    {
        return response()->json(['status' => 'success', 'reference_id' => $reference_id]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        // Tamara will send order status updates here
        return response()->json(['status' => 'received']);
    }
}
