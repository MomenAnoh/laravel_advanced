<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TapyController extends Controller
{
    public function tapy(Request $request)
    {


        $amount = 55;

        $payload = [
            'payment' => [
                'amount' => $amount,
                'currency' => 'SAR',
                'description' => 'Platform Subscription',

                'buyer' => [
                    'phone' => '+201093373197',
                    'email' => 'momen@gmail.com',
                    'name' =>'Momen',
                ],

                'buyer_history' => [
                    'registered_since' => '2023-01-01T00:00:00Z',
                    'loyalty_level' => 0,
                    'wishlist_count' => 0,
                    'is_social_networks_connected' => false,
                    'is_phone_number_verified' => true,
                    'is_email_verified' => true,
                ],

                'order_history' => [],

                'shipping_address' => [
                    'city' => 'Riyadh',
                    'address' => 'Address Line 1',
                    'zip' => '12345',
                ],

                'order' => [
                    'reference_id' => '123',

                    'tax_amount' => '0.00',
                    'shipping_amount' => '0.00',
                    'discount_amount' => '0.00',

                    'items' => [
                        [
                            'reference_id' => '123',
                            'title' => 'Platform Subscription - 1 Year',
                            'description' => 'Annual subscription renewal',
                            'quantity' => 1,
                            'unit_price' => $amount,
                            'discount_amount' => '0.00',
                            'image_url' => 'https://example.com/image.jpg',
                            'product_url' => 'https://example.com/product',
                            'category' => 'Subscription',
                        ],
                    ],
                ],
            ],

            'lang' => 'ar',

            'merchant_code' => 'AB8875T',

            'merchant_urls' => [
                'success' => route('tabby.callback'),
                'cancel' => route('tabby.callback'),
                'failure' => route('tabby.callback'),
            ],
        ];


            $response = Http::withToken("sk_test_019ee5ec-bde8-751b-93ba-a9052f07f044")
                ->acceptJson()
                ->post(
                    'https://api.tabby.ai/api/v2/checkout',
                    $payload
                );

            if ($response->failed()) {
   

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create Tabby checkout',
                    'error' => $response->json(),
                ], $response->status());
            }

            $data = $response->json();

            return response()->json([
                'success' => true,
                'message' => 'Tabby checkout created successfully',
                'data' => $data,
            ]);

    }

    public function callback(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tabby callback received',
            'data' => $request->all(),
        ]);
    }


}
