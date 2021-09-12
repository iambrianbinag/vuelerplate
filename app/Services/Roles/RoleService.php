<?php

namespace App\Services\Roles;

use App\Models\Roles\Role;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RoleService extends Service
{    
    /**
     * @var Role
     */
    protected $role;
    
    /**
     * RoleService constructor
     *
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        parent::__construct();

        $this->role = $role;
    }
    
    /**
     * Get roles
     *
     * @param string $search
     * @param int $perPage
     * @param string $orderBy
     * @param string $orderDirection
     * @param bool $notPaginated
     * 
     * @return LengthAwarePaginator|Collection
     */
    public function getRoles(
        ?string $search,
        ?int $perPage,
        ?string $orderBy,
        ?string $orderDirection,
        ?bool $notPaginated
    )
    {
        $perPage = $perPage ?? $this->paginationPerPageDefault;
        $orderDirection = $orderDirection ?? $this->paginationOrderDirectionDefault;

        $roles = Role::select('id', 'name')
            ->when($search, function($query, $search){
                return $query->where(function($query) use ($search){
                    $query->where('id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                });
            })
            ->when($orderBy, function($query, $orderBy) use ($orderDirection){
                return $query->orderBy($orderBy, $orderDirection);
            }, function($query) use ($orderDirection){
                return $query->orderBy('id', $orderDirection);
            })
            ->when($notPaginated, function($query){
                return $query->get();
            }, function($query) use ($perPage){
                return $query->paginate($perPage);
            });

        return $roles;
    }
    
    /**
     * Create a role
     *
     * @param array $data
     * 
     * @return Role
     */
    public function createRole(array $data)
    {
        $role = $this->role::create($data);

        $logData = ['attributes' => $data];
        $role->fillActivity($logData);
        $role->saveActivity('created');

        return $role;
    }
    
    /**
     * Get a role
     *
     * @param $id
     * 
     * @return Role
     */
    public function getRole($id)
    {
        $role = $this->role::findOrFail($id);

        return $role;
    }
    
    /**
     * Update a role
     *
     * @param Role $role
     * @param array $data
     * 
     * @return Role
     */
    public function updateRole(Role $role, array $data)
    {
        $logData = [
            'attributes' => [], 
            'old' => [
                'name' => $role->name,
            ],
        ];

        $role->update($data);

        $logData['attributes'] = [
            'name' => $role->name,
        ];

        $role->fillActivity($logData);
        $role->saveActivity('updated');

        return $role;
    }
    
    /**
     * Delete a role
     *
     * @param Role $role
     * 
     * @return Role
     */
    public function deleteRole(Role $role)
    {
        $role->delete();

        return $role;
    }
    
    /**
     * Get role permissions
     *
     * @param Role $role
     * 
     * @return Role
     */
    public function getRolePermissions(Role $role)
    {
        $roleWithPermissions = $role->load([
            'permissions' => function($query){
                $query->select('id', 'name', 'order')
                    ->orderBy('order', 'desc');
            }
        ]);
        $roleWithPermissions->permissions->transform(function($permission){
            return $permission->makeHidden('pivot');
        });

        return $roleWithPermissions;
    }
    
    /**
     * Give role permissions
     *
     * @param Role $role
     * @param array $permissionIds
     * 
     * @return Role
     */
    public function giveRolePermissions(Role $role, array $permissionIds)
    {
        $roleWithPermissions = $role->givePermissionTo($permissionIds);
        $roleWithPermissions->permissions->transform(function($permission){
           return $permission->only(['id', 'name']);
        });

        return $roleWithPermissions;
    }
    
    /**
     * Sync role permissions
     *
     * @param Role $role
     * @param array $permissionIds
     * 
     * @return Role
     */
    public function syncRolePermissions(Role $role, array $permissionIds)
    {
        $logData = [
            'attributes' => [], 
            'old' => [
                'permissions' => $role->permissions->pluck('name')->all(),
            ],
        ];

        $roleWithPermissions = $role->syncPermissions($permissionIds);
        $roleWithPermissions->permissions->transform(function($permission){
            return $permission->only(['id', 'name']);
        });

        $logData['attributes'] = [
            'permissions' => $role->permissions->pluck('name')->all(),
        ];

        $role->fillActivity($logData);
        $role->saveActivity('updated');

        return $roleWithPermissions;
    }
}