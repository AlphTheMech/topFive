<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeacherExpert extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'teacher_expert';

    // public static $logAttributes = [
    //     'expert_id', 'teacher_id'
    // ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults();
    // }

    public static $logName = 'Данные экспертов учителей';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'expert_id',
        'teacher_id'
    ];

    /**
     * expert
     *
     * @return HasOne
     */
    public function expert(): HasOne
    {
        return $this->hasOne(ExpertStatistics::class, 'expert_id', 'expert_id');
    }
    /**
     * personalDataExpert
     *
     * @return HasOne
     */
    public function personalDataExpert(): HasOne
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'expert_id');
    }
       
    /**
     * emailExpert
     *
     * @return HasOne
     */
    public function emailExpert(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'expert_id');
    }
}
