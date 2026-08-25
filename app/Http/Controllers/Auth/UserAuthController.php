<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserloginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Services\UserAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserAuthController extends Controller
{
    protected $UserService;
    public function __construct(UserAuthService $UserService)
    {
        $this->UserService=$UserService;
    }
    public function register(UserRegisterRequest $request)
    {
       return $this->UserService->register($request);
    }
    public function login(UserloginRequest $request)
    {
       return $this->UserService->login($request);
    }
     public function profile()
    {
         return $this->UserService->profile();

    }
    public function logout()
    {
        return $this->UserService->logout();

    }
    public function users()
    {
        return $this->UserService->users();
    }
    public function Save_FCM_TOKEN(Request $request)
    {
        $request->validate([
        'fcm_token' => 'required|string',
         ]);

         $user_id = Auth::user()->id;
          $user=User::find($user_id);
          $user->update([
            'fcm_token'=>$request->fcm_token,
          ]);

         return $user;
    }
    public function storeImage(Request $request)
    {
       $user=Auth::user();
     $test= $user->addMediaFromRequest('image')->toMediaCollection('users/images');
    //   $user->addMultipleMediaFromRequest(['images']) ; // multiable images in one step
      return response()->json($user->getFirstMediaUrl('users/images'));
        
    }
     public function updateImage(Request $request)
    {
        $user=Auth::user();
         $user->clearMediaCollection('users/images');
      $test= $user->addMediaFromRequest('image')->toMediaCollection('users/images');
      return response()->json($user->getFirstMediaUrl('users/images'));
    }
     public function deleteImage(Request $request)
    {
        $user=Auth::user();                 
        $user->clearMediaCollection('avatar');
              return response()->json('success');


    }
}
