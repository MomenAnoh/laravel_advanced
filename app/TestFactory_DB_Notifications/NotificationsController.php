<?php

namespace App\TestFactory_DB_Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends controller
{
 public function sendNotification(Request $request)
 {
   $notification=NotificationFactory::make($request->type);
   $response=$notification->send($request->to,$request->message);
  return response()->json([
    'status' => 'success',
    'data' => $response,
  ]);

 }
}
