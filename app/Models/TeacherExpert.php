<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherExpert extends Model
{
    use HasFactory;

    protected $table = 'teacher_expert';

    protected $fillable = [
        'expert_id',
        'teacher_id'
    ];
}
