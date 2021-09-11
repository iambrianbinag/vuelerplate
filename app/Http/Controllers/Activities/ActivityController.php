<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\GetActivitiesRequest;
use App\Services\Activities\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{        
    /**
     * @var ActivityService
     */
    protected $activityService;
    
    /**
     * ActivityController constructor
     *
     * @param ActivityService $activityService
     */
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Get all activity log
     *
     * @param GetActivitiesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetActivitiesRequest $request)
    {
        $activityLog = $this->activityService->getActivities(
            $request->log_name,
            $request->subject_id,
            $request->search, 
            $request->order_by,
            $request->order_direction,
            $request->not_paginated,
            $request->per_page,
            null
        );

        return response()->json($activityLog);
    }
}
