<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SubjectTests extends Model
{
    use HasFactory, LogsActivity;
    
    /**
     * primaryKey
     *
     * @var string
     */
    protected $primaryKey = 'tests_id';

    public static $logAttributes = [
        'tests_id', 'subject_of_studies_id'
    ];
    
    /**
     * getActivitylogOptions
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public static $logName = 'Данные предметов к тестам';
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'tests_id',
        'subject_of_studies_id'
    ];
}
