<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // public function generateToken()
    // {
    //     $this->api_token = Str::random();
    //     $this->save();

    //     return $this->api_token;
    // }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function  test()
    {
        return $this->belongsToMany(Tests::class, 'tests_permissions');
    }
    public function Score()
    {
        return $this->hasOne(ExpertStatistics::class, 'id', 'expert_id');
    }
    public function personalData()
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'id');
    }
    public function testPermission()
    {
        return $this->belongsToMany(Tests::class, 'tests_permissions');
    }
    // public function  testPermission()
    // {
    //     return $this->hasOne(TestsPermissions::class, 'user_id', 'id');
    // }
}
