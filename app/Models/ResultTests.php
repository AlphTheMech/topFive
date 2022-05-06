<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ResultTests extends Model
{
    use HasFactory, LogsActivity;
    
    /**
     * getActivitylogOptions
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public static $logAttributes = [
        'mark', 'user_id', 'tests_id', 'subject_id','number_of_attempts'
    ];
    
    public static $logName = 'Данные результатов тестов пользователей';
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'mark',
        'user_id',
        'tests_id',
        'subject_id',
        'number_of_attempts'
    ];
    
    /**
     * NumberAttempt
     *
     * @return void
     */
    public function NumberAttempt()
    {
        return $this->number_of_attempts;
    }    
       
    /**
     * testResult
     *
     * @return BelongsTo
     */
    public function testResult():BelongsTo
    {
        return $this->belongsTo(Tests::class, 'tests_id', 'id');
    }    
    
    /**
     * userResult
     *
     * @return BelongsTo
     */
    public function userResult():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }    
    /**
     * subjectResult
     *
     * @return BelongsTo
     */
    public function subjectResult():BelongsTo
    {
        return $this->belongsTo(SubjectOfStudies::class, 'subject_id', 'id');
    }
}
