<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $message;
    public $isDeleted;

    public function __construct(Message $message, $isDeleted = false)
    {
        $this->message = $message;
        $this->isDeleted = $isDeleted;
    }

    public function broadcastOn()
    {
        return new Channel("video.{$this->message->video_id}.{$this->message->type}");
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'is_deleted' => $this->isDeleted,
            'user' => $this->message->user->only(['id', 'name', 'user_role', 'tag']),
        ];
    }
}
