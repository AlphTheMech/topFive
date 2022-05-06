<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Session extends Model
{
    // use LogsActivity;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults();
    // }

    public static $logAttributes = [
        'user2_id', 'user1_id', 'is_block', 'blocked_by'
    ];

    public static $logName = 'Данные чатов пользователей';

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * chats
     *
     * @return HasManyThrough
     */
    public function chats(): HasManyThrough
    {
        return $this->hasManyThrough(Chat::class, Message::class);
    }


    /**
     * messages
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * deleteChats
     *
     * @return void
     */
    public function deleteChats()
    {
        $this->chats()->where('user_id', auth()->id())->delete();
    }

    /**
     * deleteMessages
     *
     * @return void
     */
    public function deleteMessages()
    {
        $this->messages()->delete();
    }


    /**
     * block
     *
     * @return void
     */
    public function block()
    {
        $this->is_block = true;
        $this->blocked_by = auth()->id();
        $this->save();
    }

    /**
     * unblock
     *
     * @return void
     */
    public function unblock()
    {
        $this->is_block = false;
        $this->blocked_by = null;
        $this->save();
    }
}
