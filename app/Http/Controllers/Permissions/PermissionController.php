<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\CreatePermissionRequest;
use App\Http\Requests\Permissions\GetPermissionsRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Models\Permissions\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{    
    /**
     * Get all permissions
     *
     * @param GetPermissionsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetPermissionsRequest $request)
    {
        $perPage = $request->per_page ?? config('settings.pagination.per_page');
        $orderBy = $request->order_by;
        $orderDirection = $request->order_direction ?? config('settings.pagination.order_direction');
        $search = $request->search;
        $notPaginated = $request->not_paginated;
        $chunkDefault = config('settings.chunk.default');

        $permissions = Permission::select('id', 'name', 'order')
            ->when($search, function($query, $search){
                return $query->where(function($query) use ($search){
                    $query->where('id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                });
            })
            ->when($orderBy, function($query, $orderBy) use ($orderDirection){
                return $query->orderBy($orderBy, $orderDirection);
            }, function($query) use ($orderDirection){
                return $query->orderBy('order', $orderDirection);
            })
            ->when($notPaginated, function($query) use ($chunkDefault){
                $permissionsChunked = [];
                $query->chunk($chunkDefault, function($permissions) use (&$permissionsChunked){
                    foreach($permissions as $permission){
                        array_push($permissionsChunked, $permission);
                    }
                });

                return collect($permissionsChunked);
            }, function($query) use ($perPage){
                return $query->paginate($perPage);
            });

        return response()->json($permissions);
    }
    
    /**
     * Create a permission
     *
     * @param CreatePermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreatePermissionRequest $request)
    {
        $data = $request->only(['name', 'order']);
        $permission = Permission::create($data);

        return response()->json([
            'id' => $permission->id,
            'name' => $permission->name,
            'order' => $permission->order,
        ]);
    }
    
    /**
     * Show a permission
     *
     * @param Permission $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        return response()->json([
            'id' => $permission->id,
            'name' => $permission->name,
            'order' => $permission->order,
        ]);
    }
    
    /**
     * Update a permission
     *
     * @param UpdatePermissionRequest $request
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $data = $request->only(['name', 'order']);
        $permission->update($data);

        return response()->json([
            'id' => $permission->id,
            'name' => $permission->name,
            'order' => $permission->order,
        ]);
    }
    
    /**
     * Delete a role
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'id' => $permission->id,
            'name' => $permission->name,
            'order' => $permission->order,
        ]);
    }
}
