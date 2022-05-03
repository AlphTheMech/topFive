<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SubjectTests extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'tests_id';

    public static $logAttributes = [
        'tests_id', 'subject_of_studies_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public static $logName = 'Данные предметов к тестам';

    protected $fillable = [
        'tests_id',
        'subject_of_studies_id'
    ];
}
