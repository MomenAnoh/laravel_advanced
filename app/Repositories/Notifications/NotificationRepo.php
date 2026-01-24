<?php
 namespace App\Repositories\Notifications;
 use App\Interface\NotificationRepoInterface;
use App\Models\Notification;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;


class NotificationRepo implements NotificationRepoInterface
{

    public function store(Request $request)
    {
     $notifications=   Notification::create([
                'receiver_id' => $request->receiver_id,
                'subject'   => $request->subject,
                'sender_id'    =>Auth::user()->id
        ]);
        return $notifications;

    }
     public function show($user_id)
     {
     $notifications= Notification::with('sender','receiver')
         ->where('receiver_id',$user_id)
         ->orWhere('sender_id',$user_id)->get();
         return $notifications;

     }
}
