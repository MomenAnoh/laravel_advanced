<?php

namespace App\Listeners;

use App\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLoginEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
//        Testjob::dispatch($event->email);

        Mail::raw('your are logged in successfully',function ($message) use ($event){
           $message->to($event->email);
       });
    }
}
