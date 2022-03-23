<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhiteListIp extends Model
{
    use HasFactory;
    public const FLD_ID = 'id';
    public const FLD_RESPONSIBLE_USER_ID = 'responsible_user_id';
    public const FLD_EMPLOYEE_ID = 'employee_id';
    public const FLD_IP_ADDRESS = 'ip_address';

    protected $table = 'ip_white_list_item';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $fillable= [
        'responsible_user_id',
        'employee_id',
        'ip_address',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id' , 'employee_id');
    }
    public function responsible()
    {
        return $this->hasOne(User::class , 'id', 'responsible_user_id');
    }

    public function scopeIpEquals($query, $ip)
    {
        return $query->where(self::FLD_IP_ADDRESS, $ip);
    }
}
