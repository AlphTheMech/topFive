<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRolesAndPermissions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions, LogsActivity;

    public static $logAttributes = [
        'email', 
        'name', 
        'avatar', 
        'ip_address', 
        'token', 
        'email_verified_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public static $logName = 'Пользователь регистрация/авторизация';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'avatar',
        'ip_address'
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

    public function testAttemptTest()
    {
        return $this->hasOne(ResultTests::class, 'user_id', 'id');
    }
}
