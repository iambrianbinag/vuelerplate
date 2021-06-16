<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends SpatiePermission
{
    use HasFactory, LogsActivity;

    protected static $logName = 'permission';

    protected static $submitEmptyLogs = false;

    protected static $logOnlyDirty = true;

    protected static $logAttributes = ['name', 'order'];
}
