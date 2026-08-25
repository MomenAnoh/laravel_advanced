<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestFlycontroller extends Controller
{
    public function test()
    {
        return response()->json([
            'message' => 'Test Fly Controller',
            'code' => 200,
            'status' => 'Success'
        ]);
    }
}
