<?php

namespace App\Http\Controllers\Roles;

use App\Events\Roles\RoleCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\GetRolesRequest;
use App\Http\Requests\Roles\GiveRolePermissionsRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\Roles\RoleResource;
use App\Services\Roles\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{        
    /**
     * @var RoleService
     */
    protected $roleService;
    
    /**
     * RoleController constructor
     *
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Get all roles
     *
     * @param  GetRolesRequest $request
     * @return JsonResponse
     */
    public function index(GetRolesRequest $request)
    {
        $roles = $this->roleService->getRoles(
            $request->search,
            $request->per_page,
            $request->order_by,
            $request->order_direction,
            $request->not_paginated
        );

        return response()->json($roles);
    }
    
    /**
     * Create a role
     *
     * @param  CreateRoleRequest $request
     * @return JsonResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $data = $request->only(['name']);
        $role = $this->roleService->createRole($data);
        broadcast(new RoleCreated($role))->toOthers();

        return new RoleResource($role);
    }
    
    /**
     * Show a role
     *
     * @param  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $role = $this->roleService->getRole($id);

        return new RoleResource($role);
    }
    
    /**
     * Update a role
     *
     * @param  UpdateRoleRequest $request
     * @param  $id
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->roleService->getRole($id);

        $data = $request->only(['name']);
        
        $role = $this->roleService->updateRole($role, $data);

        return new RoleResource($role);
    }
    
    /**
     * Delete a role
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->roleService->getRole($id);
        $this->roleService->deleteRole($role);

        return new RoleResource($role);
    }
    
    /**
     * Get role's attached permissions
     *
     * @param  $id
     * @return JsonResponse
     */
    public function getRolePermissions($id)
    {
        $role = $this->roleService->getRole($id);
        $roleWithPermissions = $this->roleService->getRolePermissions($role);

        return new RoleResource($roleWithPermissions);
    }
    
    /**
     * Give permissions to role
     *
     * @param  GiveRolePermissionsRequest $request
     * @param  $id
     * @return JsonResponse
     */
    public function giveRolePermissions(GiveRolePermissionsRequest $request, $id)
    {
        $role = $this->roleService->getRole($id);
        $roleWithPermissions = $this->roleService->giveRolePermissions($role, $request->permission_ids);

        return new RoleResource($roleWithPermissions);
    }
    
    /**
     * Sync permissions to role
     *
     * @param  GiveRolePermissionsRequest $request
     * @param  $id
     * @return JsonResponse
     */
    public function syncRolePermissions(GiveRolePermissionsRequest $request, $id)
    {
        $role = $this->roleService->getRole($id);
        $roleWithPermissions = $this->roleService->syncRolePermissions($role, $request->permission_ids);

        return new RoleResource($roleWithPermissions);
    }
    
    /**
     * Get the total role
     *
     * @return JsonResponse
     */
    public function getTotalRole()
    {
        $roleTotal = $this->roleService->getTotalRole();

        return response()->json([
            'total' => $roleTotal
        ]);
    }
}
