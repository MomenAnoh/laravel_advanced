<?php

namespace App\TestFactory_DB_Notifications;

class SmsNotifications implements NotificationsInterFace
{
 public function send($to,$message)
 {
     return response()->json([
         'status' => 'success',
         'message' => 'Sms sent to'.' '.$to,
     ]);
 }
}
