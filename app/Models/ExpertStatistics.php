<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExpertStatistics extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'test_id', 'expert_id', 'statistics_score',
    ];

    protected $primaryKey = 'test_id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

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
