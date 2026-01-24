<?php

namespace App\Interface;

use Illuminate\Http\Request;


interface FireBaseNotificationInterface
{
    public function Send(Request $request);

}
