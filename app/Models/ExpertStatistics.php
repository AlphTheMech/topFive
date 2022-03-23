<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertStatistics extends Model
{
    use HasFactory;

    protected $table='expert_statistics';

    protected $fillable=[
        'test_id',
        'expert_id',
        'statistics_score'
    ];
}
