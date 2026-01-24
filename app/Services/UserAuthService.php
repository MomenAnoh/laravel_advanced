<?php

namespace App\Services;
use App\Events\Login;
use App\Http\Resources\AuthResources;
use App\Http\Resources\BasicResources;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
class UserAuthService{

    public function register($request)
    {

        $user=User::create([
            'email'=>$request->email,
            'name'=>$request->name,
            'password'=>Hash::make($request->password),
        ]);
        // if($request->name == 'Momen')
        // {
        //     $user->assignRole('Admin');
        // }
        // else{
        //     $user->assignRole('User');
        // }
        $token=$user->createToken('user_token')->plainTextToken;
        return AuthResources::make($user)->additional([
            'token'=>$token
        ]);
    }
    public function login($request)
    {

        $user=User::where('email',$request->email)->first();

        if(!Hash::check($request->password,$user->password))
        {
            return BasicResources::make(null)->error('password not correct');
        }
        $token=$user->createToken('user_token')->plainTextToken;
        //  Login::dispatch($user->email);
         return AuthResources::make($user)->additional([
            'token'=>$token
        ]);
    }
     public function profile()
    {

        $user_id=Auth::user()->id;
       $user= Cache::remember("user_profile_{$user_id}", 600, function ()use($user_id) {
            return User::find($user_id);
        });
        return BasicResources::make( $user);
    }
     public function logout()
    {

       $user=Auth::user();
       $user->currentAccessToken()->delete();
       return response()->json('logout created successfully');
    }
    public function users()
    {
        $users=User::get();
        return BasicResources::make($users);
    }
}
