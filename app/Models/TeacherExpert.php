<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeacherExpert extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'teacher_expert';

    public static $logAttributes = [
        'expert_id', 'teacher_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    
    public static $logName = 'Данные экспертов учителей';

    protected $fillable = [
        'expert_id',
        'teacher_id'
    ];

    public function expert()
    {
        return $this->hasOne(ExpertStatistics::class, 'expert_id', 'expert_id');
    }
    public function personalDataExpert()
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'expert_id');
    }
    public function emailExpert()
    {
        return $this->hasOne(User::class, 'id', 'expert_id');
    }
}
