<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * content
     *
     * @var mixed
     */
    public $content;    
    /**
     * chat
     *
     * @var mixed
     */
    public $chat;

    
    /**
     * __construct
     *
     * @param  mixed $content
     * @param  mixed $chat
     * @return void
     */
    public function __construct($content, $chat)
    {
        $this->content = $content;
        $this->chat = $chat;
        $this->dontBroadcastToCurrentUser();
    }

    
    /**
     * broadcastOn
     *
     * @return void
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Chat.'. $this->chat['session_id']);
    }
}
