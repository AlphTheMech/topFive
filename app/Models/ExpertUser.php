<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertUser extends Model
{
    use HasFactory;
    
    /**
     * table
     *
     * @var string
     */
    protected $table = 'expert_user';
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'test_id',
        'expert_id'
    ];
}
