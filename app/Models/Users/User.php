<?php

namespace App\Models\Users;

use App\Services\Activities\Log\ActivityLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard_name ='api';

    private static $logName = 'user';

    private static $submitEmptyLogs = false;

    private static $logOnlyDirty = true;

    private static $activity = [];

    private static $validActivityDescriptions = ['created', 'updated', 'deleted'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['id' => $this->id];
    }

    /**
     * Get first role and it's permission instead of multiple roles
     */
    public function getRoleAttribute()
    {
        $role = null;
        if($firstRole = $this->roles->first()){
            $firstRole->load('permissions:id,name');
            $firstRole->permissions->transform(function($permission){
                return $permission->makeHidden('pivot');
            });
            $role = [
                'id' => $firstRole->id,
                'name' => $firstRole->name,
                'permissions' => $firstRole->permissions
            ];
        }

        return  $role;
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
