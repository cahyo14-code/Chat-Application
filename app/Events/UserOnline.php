<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnline implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $status;

    public function __construct(User $user, string $status)
    {
        $this->user   = $user;
        $this->status = $status;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('online'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'name'    => $this->user->name,
            'status'  => $this->status,
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.status';
    }
}