<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectOfStudies;
use LDAP\Result;

class Tests extends Model
{
    use HasFactory;
    
    public static $logAttributes = [
        'name_test', 'json_data'
    ];

    public static $logName = 'Данные тестов';

    protected $fillable = [
        'name_test',
        'json_data'
    ];
    protected $casts = [
        'json_data' => 'array'
    ];
    public function subjectTests()
    {
        return $this->belongsToMany(SubjectOfStudies::class, 'subject_tests');
    }
    public function permissionTest()
    {
        return $this->belongsToMany(User::class, 'tests_permissions');
    }
    public function resultTest()
    {
        return $this->hasOne(ResultTests::class, 'tests_id', 'id');
        // return $this->belongsTo(ResultTests::class, 'test_id');
    }
}
