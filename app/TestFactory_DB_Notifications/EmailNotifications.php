<?php

namespace App\TestFactory_DB_Notifications;

class EmailNotifications implements NotificationsInterFace
{
   public function send($to,$message)
   {
       return response()->json([
           'status' => 'success',
           'message' => 'Email sent to'.' '.$to,
       ]);
   }
}
