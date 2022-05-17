<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];    
    /**
     * chats
     *
     * @return object
     */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    
    /**
     * createForSend
     *
     * @param  mixed $session_id
     * @return void
     */
    public function createForSend($session_id)
    {
        return $this->chats()->create([
            'session_id' => $session_id,
            'type' => 0,
            'user_id' => auth()->id()]);
    }
    
    /**
     * createForReceive
     *
     * @param  mixed $session_id
     * @param  mixed $to_user
     * @return void
     */
    public function createForReceive($session_id, $to_user)
    {
        return $this->chats()->create([
            'session_id' => $session_id,
            'type' => 1,
            'user_id' => $to_user]);
    }
}
