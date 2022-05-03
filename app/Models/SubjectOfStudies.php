<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tests;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SubjectOfStudies extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public static $logName = 'Данные предметов';

    protected $fillable = [
        'name',
    ];

    public function subjectStudy()
    {
        return $this->belongsToMany(Tests::class, 'subject_tests');
    }
    
    public function subjectId()
    {
        return $this->id;
    }
}
