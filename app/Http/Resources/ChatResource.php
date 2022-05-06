<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    
    /**
     * toArray
     *
     * @param  mixed $request
     * @return void
     */
    public function toArray($request)
    {
        return [
            'message' => $this->message['content'],
            'id' => $this->id,
            'type' => $this->type,
            'read_at' => $this->read_at_timing($this),
            'send_at' =>Carbon::parse($this->created_at) ->diffForHumans()
        ];
    }    
    /**
     * read_at_timing
     *
     * @param  mixed $_this
     * @return void
     */
    private function read_at_timing($_this)
    {
        $read_at = $_this->type == 0 ? $_this->read_at : null;
        return $read_at ? $read_at->diffForHumans() : null;
    }
}
