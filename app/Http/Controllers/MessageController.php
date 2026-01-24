<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function userMessages($id)
    {
        $messages = Message::where(function($q) use ($id) {
            $q->where('user_id', auth()->id())
                ->where('receiver_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('user_id', $id)
                ->where('receiver_id', auth()->id());
        })->with('user','receiver')->get();
        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function store(Request $request,$id)
    {
        $user_id = auth()->user()->id;
        $validated = $request->validate([
        'body' => 'required',
        ]);

        $message = Message::create([
            'receiver_id'=>$id,
            'user_id' => $user_id,
            'body' => $validated['body'],
        ]);
        
      // call this event now
        broadcast(new MessageSent($message))->toOthers();
        //toOthers() will send the event to all users except the sender
        $message->load('user','receiver');
        return response()->json([
            'message' => $message,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
