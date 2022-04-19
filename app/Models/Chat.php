<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{    
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime'
    ];
    
    /**
     * message
     *
     * @return void
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
