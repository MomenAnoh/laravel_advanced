<?php

namespace App\Http\Controllers\V1;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function users()
    {
        return response()->json('user of V1');
    }
    public function googleLogin(Request $request)
    {
        try {

        $request->validate([
            'access_token' => 'required|string',
        ]);
        // to blade  return Socialite::driver('google')->redirect();
        $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->access_token);

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(str()->random(16)),
            ]
        );
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid Google token',
            'error' => $e->getMessage(),
        ], 401);
    }
    }
    public function facebookLogin(Request $request)
    {
        try {
            $request->validate([
                'access_token' => 'required|string',
            ]);
            $facebookUser = Socialite::driver('facebook')->stateless()->userFromToken($request->access_token);
            $email=$facebookUser->email ? $facebookUser->email : "facebookUser{$facebookUser->id}@facebook.com";
            $user=User::where('facebook_id',$facebookUser->id)->orwhere('email',$email)->first();
            if(!$user)
            {
                $user = User::Create(
                    [
                        'facebook_id' => $facebookUser->id,
                        'name' => $facebookUser->name,
                        'email' => $email,
                        'password' => bcrypt(str()->random(16)),
                    ]
                );
            }

            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid facebook token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
}
