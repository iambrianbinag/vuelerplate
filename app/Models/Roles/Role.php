<?php

namespace App\Models\Roles;

use App\Services\Activities\Log\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use DateTimeInterface;

class Role extends SpatieRole
{
    use HasFactory;

    private static $logName = 'role';

    private static $submitEmptyLogs = false;

    private static $logOnlyDirty = true;

    private static $activity = [];

    private static $validActivityDescriptions = ['created', 'updated', 'deleted'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
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

    /**
     * Fill activities log
     *
     * @param array $activities
     * @return array
     */
    public function fillActivity(array $activities)
    {
        $this->activity = $activities;
        return $this->activity;
    }

    /**
     * Save custom activity log
     *
     * @param string $description
     * @return \Spatie\Activitylog\Contracts\Activity
     */
    public function saveActivity(string $description)
    {
        $activityLog = new ActivityLog();
        return $activityLog
            ->validateDescription($description, self::$validActivityDescriptions)
            ->setLogName(self::$logName)
            ->setSubmitEmptyLogs(self::$submitEmptyLogs)
            ->setLogOnlyDirty(self::$logOnlyDirty)
            ->setPerformedOn($this)
            ->setCausedBy(auth()->user())
            ->setWithProperties($this->activity)
            ->setDescription($description)
            ->save();
    }
}
