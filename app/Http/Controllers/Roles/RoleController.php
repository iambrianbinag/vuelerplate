<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\GetRolesRequest;
use App\Http\Requests\Roles\GiveRolePermissionsRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
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
     * @param GetRolesRequest $request
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
     * @param CreateRoleRequest $request
     * @return JsonResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $data = $request->only(['name']);
        $role = $this->roleService->createRole($data);

        return response()->json([
            'id' => $role->id,
            'name' => $role->name
        ]);
    }
    
    /**
     * Show a role
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $role = $this->roleService->getRole($id);

        return response()->json([
            'id' => $role->id,
            'name' => $role->name
        ]);
    }
    
    /**
     * Update a role
     *
     * @param UpdateRoleRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->roleService->getRole($id);

        $data = $request->only(['name']);
        
        $role = $this->roleService->updateRole($role, $data);

        return response()->json([
            'id' => $role->id,
            'name' => $role->name
        ]);
    }
    
    /**
     * Delete a role
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->roleService->getRole($id);
        $this->roleService->deleteRole($role);

        return response()->json([
            'id' => $role->id,
            'name' => $role->name
        ]);
    }
    
    /**
     * Get role's attached permissions
     *
     * @param $id
     * @return JsonResponse
     */
    public function getRolePermissions($id)
    {
        $role = $this->roleService->getRole($id);
        $roleWithPermissions = $this->roleService->getRolePermissions($role);

        return response()->json([
            'id' => $roleWithPermissions->id,
            'name' => $roleWithPermissions->name,
            'permissions' => $roleWithPermissions->permissions
        ]);
    }
    
    /**
     * Give permissions to role
     *
     * @param GiveRolePermissionsRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function giveRolePermissions(GiveRolePermissionsRequest $request, $id)
    {
        $role = $this->roleService->getRole($id);
        $roleWithPermissions = $this->roleService->giveRolePermissions($role, $request->permission_ids);

        return response()->json([
            'id' => $roleWithPermissions->id,
            'name' => $roleWithPermissions->name,
            'permissions' => $roleWithPermissions->permissions
        ]);
    }
    
    /**
     * Sync permissions to role
     *
     * @param GiveRolePermissionsRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function syncRolePermissions(GiveRolePermissionsRequest $request, $id)
    {
        $role = $this->roleService->getRole($id);
        $roleWithPermissions = $this->roleService->syncRolePermissions($role, $request->permission_ids);

        return response()->json([
            'id' => $roleWithPermissions->id,
            'name' => $roleWithPermissions->name,
            'permissions' => $roleWithPermissions->permissions
        ]);
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
