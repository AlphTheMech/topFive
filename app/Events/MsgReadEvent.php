<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MsgReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * chat
     *
     * @var mixed
     */
    public $chat;    
    /**
     * session_id
     *
     * @var mixed
     */
    private $session_id;
    
    /**
     * __construct
     *
     * @param  mixed $chat
     * @param  mixed $session_id
     * @return void
     */
    public function __construct($chat, $session_id)
    {
        $this->chat = $chat;
        $this->session_id = $session_id;
        $this->dontBroadcastToCurrentUser();
    }
    
    /**
     * broadcastOn
     *
     * @return void
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Chat.'. $this->session_id);
    }
}
