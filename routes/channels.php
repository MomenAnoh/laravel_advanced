<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('public.chat', function ($user) {
    return true;
});
Broadcast::channel('Private.chat.{receiverId}', function ($user, $receiverId) {
    // يسمح فقط للمستخدم المستقبل بالاشتراك في القناة
    return (int) $user->id === (int) $receiverId;
});
