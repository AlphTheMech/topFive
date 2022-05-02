<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UsersPermissions extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_id'
    ];

    public static $logAttributes = [
        'user_id', 'permission_id'
    ];

    public static $logName = 'Данные прав пользователей';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
