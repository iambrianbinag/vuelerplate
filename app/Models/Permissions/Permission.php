<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Activitylog\Traits\LogsActivity;
use DateTimeInterface;

class Permission extends SpatiePermission
{
    use HasFactory, LogsActivity;

    protected static $logName = 'permission';

    protected static $submitEmptyLogs = false;

    protected static $logOnlyDirty = true;

    protected static $logAttributes = ['name', 'order'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'guard_name'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
