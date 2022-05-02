<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class ExpertUser extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'test_id', 'user_id', 'expert_id',
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }

    public static $logName = 'Данные пользователей экспертов';
    /**
     * table
     *
     * @var string
     */
    protected $table = 'expert_user';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'test_id',
        'expert_id'
    ];
}
