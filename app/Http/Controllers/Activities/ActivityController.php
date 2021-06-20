<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\GetActivitiesRequest;
use App\Models\Activities\Activity;
use App\Utilities\Utils;
use Illuminate\Http\Request;

class ActivityController extends Controller
{    
    /**
     * Get all activity log
     *
     * @param GetActivitiesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetActivitiesRequest $request)
    {
        $perPage = $request->per_page ?? config('settings.pagination.per_page');
        $orderBy = $request->order_by;
        $orderDirection = $request->order_direction ?? config('settings.pagination.order_direction');
        $search = $request->search;
        $notPaginated = $request->not_paginated;
        $chunkDefault = config('settings.chunk.default');
        $logName = $request->log_name;
        $subjectId = $request->subject_id;

        $activityLog = Activity::with('subject', 'causer')
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
            ->when($notPaginated, function($query) use ($chunkDefault){
                $activityLogChunked = [];
                $query->chunk($chunkDefault, function($activities) use (&$activityLogChunked){
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
           $activityLog = Utils::arrayMapWithAllowedKeys($activityLog, $onlyAttributes);
        } else {
            $activityLog['data'] = Utils::arrayMapWithAllowedKeys($activityLog['data'], $onlyAttributes);
        }
        
        return response()->json($activityLog);
    }
}
