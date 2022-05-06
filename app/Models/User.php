<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    // protected $with = ['test', 'Score', 'personalData', 'testPermission', 'testAttemptTest', 'list'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * test
     *
     * @return BelongsToMany
     */
    public function  test(): BelongsToMany
    {
        return $this->belongsToMany(Tests::class, 'tests_permissions');
    }
    
    /**
     * Score
     *
     * @return HasOne
     */
    public function Score(): HasOne
    {
        return $this->hasOne(ExpertStatistics::class, 'id', 'expert_id');
    }
    
    /**
     * personalData
     *
     * @return HasOne
     */
    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'id');
    }
    
    /**
     * testPermission
     *
     * @return BelongsToMany
     */
    public function testPermission(): BelongsToMany
    {
        return $this->belongsToMany(Tests::class, 'tests_permissions');
    }
    
    /**
     * testAttemptTest
     *
     * @return HasOne
     */
    public function testAttemptTest(): HasOne
    {
        return $this->hasOne(ResultTests::class, 'user_id', 'id');
    }    
    /**
     * list
     *
     * @return HasOne
     */
    public function list(): HasOne
    {
        return $this->hasOne(WhiteListIP::class, 'user_id', 'id');
    }
}
