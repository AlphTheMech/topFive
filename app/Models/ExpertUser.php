<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ExpertUser extends Model
{
    use HasFactory;

    public static $logAttributes = [
        'test_id', 'user_id', 'expert_id',
    ];
    
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
