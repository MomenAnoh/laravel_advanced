<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
class testSmsNotification extends Controller
{
    public function sendOtp(Request $request)
    {


        $twilio = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        $otp_code=rand(100000,999999);
        $message = $twilio->messages->create(
            "+966501234567",
            [
                "from" => env('TWILIO_PHONE_NUMBER'),
                "body" => "رمز التفعيل الخاص بك هو: " . $otp_code . ". صالح لمدة 5 دقائق.",
            ]
        );
       dd($message);
        return response()->json([
            'message' => 'OTP sent successfully',
            'sid' => $message->sid
        ]);
    }
}
