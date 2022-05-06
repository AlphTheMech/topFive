<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tests;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SubjectOfStudies extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'name'
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

    public static $logName = 'Данные предметов';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * subjectStudy
     *
     * @return BelongsToMany
     */
    public function subjectStudy(): BelongsToMany
    {
        return $this->belongsToMany(Tests::class, 'subject_tests');
    }    
    /**
     * subjectResult
     *
     * @return HasOne
     */
    public function subjectResult(): HasOne
    {
        return $this->hasOne(ResultTests::class, 'subject_id', 'id');
    }
    /**
     * subjectId
     *
     * @return void
     */
    public function subjectId()
    {
        return $this->id;
    }
}
