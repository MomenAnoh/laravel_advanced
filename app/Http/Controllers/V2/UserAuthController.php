<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;

class UserAuthController extends Controller
{
   public function users()
   {
       return response()->json('data of V2');
   }
}
