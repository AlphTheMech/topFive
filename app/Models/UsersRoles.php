<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class UsersRoles extends Model
{
    use HasFactory, LogsActivity;
    
    protected $fillable=[
        'user_id',
        'role_id'
    ];
    public static $logAttributes = [
        'user_id', 'role_id'
    ];

    public static $logName = 'Данные ролей пользователя';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        
    }
}
