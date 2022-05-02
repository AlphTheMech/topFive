<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ExpertStatistics extends Model
{
    use HasFactory;

    public static $logAttributes = [
        'test_id', 'expert_id', 'statistics_score',
    ];
    
    public static $logName = 'Данные статистики экспертов';

    /**
     * table
     *
     * @var string
     */
    protected $table = 'expert_statistics';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'test_id',
        'expert_id',
        'statistics_score'
    ];
}
