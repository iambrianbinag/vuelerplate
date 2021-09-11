<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\CreatePermissionRequest;
use App\Http\Requests\Permissions\GetPermissionsRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Models\Permissions\Permission;
use App\Services\Permissions\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{    
    
    /**
     * @var PermissionService
     */
    protected $permissionService;
    
    /**
     * PermissionController constructor
     *
     * @param PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Get all permissions
     *
     * @param GetPermissionsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetPermissionsRequest $request)
    {
        $permissions = $this->permissionService->getPermissions(
            $request->search,
            $request->per_page,
            $request->order_by,
            $request->order_direction,
            $request->not_paginated,
            null
        );       

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
        $permission = $this->permissionService->createPermission($data);

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
