<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

// Channel untuk conversation (hanya anggota yang bisa masuk)
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    if ($conversation && $conversation->users->contains($user->id)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    return false;
});

// Channel untuk tracking online/offline semua user
Broadcast::channel('online', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});