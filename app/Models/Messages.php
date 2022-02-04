<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'dialog_id',
        'message_id',
        'author_id',
        'content'
    ];
}
