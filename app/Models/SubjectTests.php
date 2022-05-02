<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTests extends Model
{
    use HasFactory;

    public static $logAttributes = [
        'tests_id', 'subject_of_studies_id'
    ];
    
    public static $logName = 'Данные предметов к тестам';

    protected $fillable = [
        'tests_id',
        'subject_of_studies_id'
    ];
}
