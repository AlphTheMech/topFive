<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TestsPermissions extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'user_id', 'tests_id'
    ];

    public static $logName = 'Данные доступа к тесту';

    protected $fillable = [
        'user_id',
        'tests_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
}
