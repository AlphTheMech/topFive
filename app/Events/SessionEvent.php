<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * session
     *
     * @var mixed
     */
    public $session;    
    /**
     * session_by
     *
     * @var mixed
     */
    public $session_by;

    
    /**
     * __construct
     *
     * @param  mixed $session
     * @param  mixed $session_by
     * @return void
     */
    public function __construct($session, $session_by)
    {
        $this->session = $session;
        $this->session_by = $session_by;
        $this->dontBroadcastToCurrentUser();
    }

    
    /**
     * broadcastOn
     *
     * @return void
     */
    public function broadcastOn()
    {
        return new Channel('Chat');
    }
}
