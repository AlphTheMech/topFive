<?php

namespace App\Models;

// use App\Models\Storage\User\Employee;
// use App\Models\Storage\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class WhiteListIP extends Model
{
    use LogsActivity;

    public const FLD_ID = 'id';
    public const FLD_RESPONSIBLE_USER_ID = 'responsible_user_id';
    public const FLD_EMPLOYEE_ID = 'employee_id';
    public const FLD_IP_ADDRESS = 'ip_adress';
    public const FLD_CREATED_BY = 'created_by';
    public const FLD_UPDATED_BY = 'updated_by';

    public static $logAttributes = [
        'user_id', 'ip_address'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }


    public static $logName = 'IP адреса админов';

    protected $table = 'white_list_ip';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $dates = ['created_by', 'updated_by'];

    protected $fillable = [
        'ip_address',
        'user_id'
    ];

    // public function is_owner()
    // {
    //     return $this->employee_id; 
    // }
    // public function employee()
    // {
    //     return $this->hasOne(Employee::class, 'id' , 'employee_id');
    // }
    public function responsible()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function personalData()
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'user_id');
    }
}
