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

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    // Broadcast ke channel conversation
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    // Data yang dikirim ke frontend
    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'body'       => $this->message->body,
            'user_id'    => $this->message->user_id,
            'user_name'  => $this->message->user->name,
            'created_at' => $this->message->created_at->format('H:i'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}