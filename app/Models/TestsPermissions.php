<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestsPermissions extends Model
{
    use HasFactory;

    public static $logAttributes = [
        'user_id', 'tests_id'
    ];

    public static $logName = 'Данные доступа к тесту';

    protected $fillable = [
        'user_id',
        'tests_id'
    ];
}
