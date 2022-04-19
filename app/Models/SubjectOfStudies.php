<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tests;

class SubjectOfStudies extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function subjectStudy()
    {
        return $this->belongsToMany(Tests::class, 'subject_tests');
    }
    public function subjectId()
    {
        return $this->id;
    }
}
