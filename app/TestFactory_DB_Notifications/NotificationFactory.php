<?php

namespace App\TestFactory_DB_Notifications;

class NotificationFactory
{
  public static function make($type) : NotificationsInterFace
  {
      return match ($type) {
          'email' => new EmailNotifications(),
          'sms' => new SmsNotifications(),
          default => throw new \Exception("Unknown type: $type"),
      };
  }
}
