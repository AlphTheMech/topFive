<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PersonalData extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        
    }
    
    public static $logAttributes = [
        'first_name', 'middle_name', 'last_name', 'user_id'
    ];
    
    public static $logName = 'Данные персональных данных пользователей';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'user_id'
    ];
}
