<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectOfStudies;
class Tests extends Model
{
    use HasFactory;
    protected $fillable=[
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
}
