<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SubjectTests extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'tests_id', 'subject_of_studies_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }

    public static $logName = 'Данные предметов к тестам';

    protected $fillable = [
        'tests_id',
        'subject_of_studies_id'
    ];
}
