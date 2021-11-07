<?php

namespace App\Http\Controllers\Permissions;

use App\Events\Permissions\PermissionCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\CreatePermissionRequest;
use App\Http\Requests\Permissions\GetPermissionsRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Http\Resources\Permissions\PermissionResource;
use App\Services\Permissions\PermissionService;
use Illuminate\Http\JsonResponse;
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
     * @param  PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Get all permissions
     *
     * @param  GetPermissionsRequest $request
     * @return JsonResponse
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
     * @param  CreatePermissionRequest $request
     * @return JsonResponse
     */
    public function store(CreatePermissionRequest $request)
    {
        $data = $request->only(['name', 'order']);
        $permission = $this->permissionService->createPermission($data);
        broadcast(new PermissionCreated($permission))->toOthers();

        return new PermissionResource($permission);
    }
    
    /**
     * Show a permission
     *
     * @param  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $permission = $this->permissionService->getPermission($id);

        return new PermissionResource($permission);
    }
    
    /**
     * Update a permission
     *
     * @param  UpdatePermissionRequest $request
     * @param  $id
     * @return JsonResponse
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = $this->permissionService->getPermission($id);

        $data = $request->only(['name', 'order']);

        $permission = $this->permissionService->updatePermission($permission, $data);

        return new PermissionResource($permission);
    }
    
    /**
     * Delete a role
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $permission = $this->permissionService->getPermission($id);
        $this->permissionService->deletePermission($permission);

        return new PermissionResource($permission);
    }
    
    /**
     * Get the total permission
     *
     * @return JsonResponse
     */
    public function getTotalPermission()
    {
        $permissionTotal = $this->permissionService->getTotalPermission();

        return response()->json([
            'total' => $permissionTotal
        ]);
    }
}
