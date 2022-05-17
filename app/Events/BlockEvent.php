<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlockEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * session_id
     *
     * @var mixed
     */
    public $session_id;
    
    /**
     * blocked
     *
     * @var mixed
     */
    public $blocked;

    /**
     * __construct
     *
     * @param  mixed $session_id
     * @param  mixed $blocked
     * @return void
     */
    public function __construct($session_id, $blocked)
    {
        $this->session_id = $session_id;
        $this->blocked = $blocked;
        $this->dontBroadcastToCurrentUser();
    }

    
    /**
     * broadcastOn
     *
     * @return void
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Chat.' . $this->session_id);
    }
}
