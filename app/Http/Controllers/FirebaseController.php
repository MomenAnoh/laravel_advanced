<?php

namespace App\Http\Controllers;
use App\Interface\FireBaseNotificationInterface;
use App\Services\NotificationServiece;
use App\Http\Requests\SendNotificationRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FirebaseController implements FireBaseNotificationInterface
{
    protected $notification;
    public function __construct(NotificationServiece $notification)
    {
        $this->notification=$notification;
    }

    public function send(Request $request)
    {
        return $this->notification->send($request);
    }

    public function UserNotifications()
    {
     return $this->notification->UserNotifications();

    }
}
