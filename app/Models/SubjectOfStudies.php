<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tests;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function subjectStudy():BelongsToMany
    {
        return $this->belongsToMany(Tests::class, 'subject_tests');
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
