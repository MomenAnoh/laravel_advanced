<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast // must make use this
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $message;
    public function __construct(Message $message) // Use Model  to use columns body and user_id
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {

        return [
            new PrivateChannel('Private.chat.' . $this->message->receiver_id),
            new PrivateChannel('Private.chat.' . $this->message->user_id),
        ];
    }
    public function broadcastWith(): array // this return when broadcastOn is called ********
    {
        return [
            'message' => [
                'body' => $this->message->body,
                'created_at' => $this->message->created_at->diffForHumans(),
                'user_id' => $this->message->user_id, // أضف معرف المستخدم
                'receiver_id' => $this->message->receiver_id, // **أضف هذا السطر**

                'user' => [
                    'name' => $this->message->user->name,
                    'id' => $this->message->user->id,
                ],
            ]
        ];
    }
}
