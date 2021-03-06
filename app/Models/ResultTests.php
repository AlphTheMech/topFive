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
        'test_id',
        'subject_id',
        'number_of_attempts'
    ];

    public function NumberAttempt()
    {
        return $this->number_of_attempts;
    }
}
