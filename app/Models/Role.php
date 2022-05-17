<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Role extends Model
{
    use HasFactory, LogsActivity;
    
    /**
     * timestamps
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    public static $logAttributes = [
        'name', 'slug'
    ];

    public static $logName = 'Данные ролей';
    
    /**
     * getActivitylogOptions
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    
    /**
     * permissions
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }
}
