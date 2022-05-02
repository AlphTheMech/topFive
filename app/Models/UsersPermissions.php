<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersPermissions extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'user_id',
        'permission_id'
    ];
}
