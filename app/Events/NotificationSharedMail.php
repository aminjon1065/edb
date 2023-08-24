<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationSharedMail implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $message, int $userId)
    {
        $this->message = $message;
        $this->userId = $userId;

        Log::info('NotificationSharedMail event has been fired.', [  // Запись в лог
            'message' => $this->message,
            'userId' => $this->userId
        ]);
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notification.' . $this->userId)
        ];
    }
}
