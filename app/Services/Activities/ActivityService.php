<?php

namespace App\Services\Activities;

use App\Models\Activities\Activity;
use App\Services\Service;
use App\Utils\Util;

class ActivityService extends Service
{          
    /**
     * @var Activity
     */
    protected $activity;
    
    /**
     *  ActivityService constructor
     */
    public function __construct(Activity $activity)
    {
        parent::__construct();

        $this->activity = $activity;
    }
    
    /**
     * Get activity log
     * 
     * @param string $logName
     * @param int $subjectId
     * @param string $search
     * @param string $orderBy
     * @param string $orderDirection
     * @param bool $notPaginated 
     * @param int $perPage
     * @param int $queryChunk
     *
     * @return array
     */
    public function getActivities(
        ?string $logName, 
        ?int $subjectId, 
        ?string $search,
        ?string $orderBy, 
        ?string $orderDirection,
        ?bool $notPaginated,
        ?int $perPage,
        ?int $queryChunk
    )
    {
        $perPage = $perPage ?? $this->paginationPerPageDefault;
        $orderDirection = $orderDirection ?? $this->paginationOrderDirectionDefault;
        $queryChunk = $queryChunk ?? $this->queryChunkDefault;

        $activityLog = $this->activity::with('subject', 'causer')
            ->when($logName, function($query, $logName){
                return $query->where('log_name', $logName);
            })
            ->when($subjectId, function($query, $subjectId){
                return $query->where('subject_id', $subjectId);
            })
            ->when($search, function($query, $search){
                return $query->where(function($query) use ($search){
                    $query->where('id', 'like', "%$search%")
                        ->orWhere('log_name', 'like', "%$search%");
                });
            })
            ->when($orderBy, function($query, $orderBy) use ($orderDirection){
                return $query->orderBy($orderBy, $orderDirection);
            }, function($query) use ($orderDirection){
                return $query->orderBy('created_at', $orderDirection);
            })
            ->when($notPaginated, function($query) use ($queryChunk){
                $activityLogChunked = [];
                $query->chunk($queryChunk, function($activities) use (&$activityLogChunked){
                    foreach($activities as $activity){
                        array_push($activityLogChunked, $activity);
                    }
                });

                return collect($activityLogChunked);
            }, function($query) use ($perPage){
                return $query->paginate($perPage);
            })
            ->toArray();

        $onlyAttributes = [
            'id', 'log_name', 'description', 'created_at', 'updated_at', 'changes', 'subject', 'causer'
        ];

        if($notPaginated){
           $activityLog = Util::arrayMapWithAllowedKeys($activityLog, $onlyAttributes);
        } else {
            $activityLog['data'] = Util::arrayMapWithAllowedKeys($activityLog['data'], $onlyAttributes);
        }
        
        return $activityLog;
    }
}