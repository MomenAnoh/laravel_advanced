<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicResources extends JsonResource
{
    public function result($data,$message = null ,$code = 200)
    {
        return response()->json([

            'status'=>'true',
            'data'=>$data,
            'code'=>$code,
            'message'=>$message,
        ]);
    }
    public function error($message = null ,$code = 400)
    {
        return response()->json([
            'status'=>'false',
            'code'=>$code,
            'message'=>$message,
        ]);
    }
    public function delete($message = null ,$code = 200)
    {
        return response()->json([
            'code'=>$code,
            'status'=>'true',
            'message'=>$message !== null ?$message:'Item deleted successfully',
        ]);
    }
}
