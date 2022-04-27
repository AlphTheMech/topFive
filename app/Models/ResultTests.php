<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultTests extends Model
{
    use HasFactory;
    protected $fillable = [
        'mark',
        'user_id',
        'tests_id',
        'subject_id',
        'number_of_attempts'
    ];

    public function NumberAttempt()
    {
        return $this->number_of_attempts;
    }
    public function testResult()
    {
        return $this->belongsTo(Tests::class, 'tests_id', 'id');
    }
    public function userResult()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function subjectResult()
    {
        return $this->belongsTo(SubjectOfStudies::class, 'subject_id', 'id');
    }
}
