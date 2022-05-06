<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectOfStudies;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tests extends Model
{
    use HasFactory, LogsActivity;

    public static $logAttributes = [
        'name_test', 'json_data'
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

    public static $logName = 'Данные тестов';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name_test',
        'json_data'
    ];
    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'json_data' => 'array'
    ];

    /**
     * subjectTests
     *
     * @return BelongsToMany
     */
    public function subjectTests(): BelongsToMany
    {
        return $this->belongsToMany(SubjectOfStudies::class, 'subject_tests');
    }

    /**
     * permissionTest
     *
     * @return BelongsToMany
     */
    public function permissionTest(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tests_permissions');
    }

    /**
     * resultTest
     *
     * @return HasOne
     */
    public function resultTest(): HasOne
    {
        return $this->hasOne(ResultTests::class, 'tests_id', 'id');
    }
}
