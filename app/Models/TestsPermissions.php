<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestsPermissions extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'tests_id'
    ];
}
