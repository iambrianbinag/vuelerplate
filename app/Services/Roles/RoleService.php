<?php

namespace App\Services\Roles;

use App\Models\Roles\Role;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Services\Cache\Interfaces\CacheInterface;

class RoleService extends Service
{    
    /**
     * @var Role
     */
    protected $role;
    
    /**
     * @var CacheInterface
     */
    protected $cacheService;
    
    /**
     * RoleService constructor
     *
     * @param  Role $role
     * @param  CacheInterface $cacheService
     */
    public function __construct(Role $role, CacheInterface $cacheService)
    {
        parent::__construct();

        $this->role = $role;

        $this->cacheService = $cacheService;
    }
    
    /**
     * Get roles
     *
     * @param  string $search
     * @param  int $perPage
     * @param  string $orderBy
     * @param  string $orderDirection
     * @param  bool $notPaginated
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
     * @param  array $data
     * @return Role
     */
    public function createRole(array $data)
    {
        $role = $this->role::create($data);

        $logData = ['attributes' => $data];
        $role->fillActivity($logData);
        $role->saveActivity('created');

        $this->setTotalRoleFromCache($this->getTotalRole() + 1);

        return $role;
    }
    
    /**
     * Get a role
     *
     * @param  $id
     * @return Role
     */
    public function getRole($id)
    {
        return $this->role::findOrFail($id);
    }
    
    /**
     * Update a role
     *
     * @param  Role $role
     * @param  array $data
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
     * @param  Role $role
     * @return Role
     */
    public function deleteRole(Role $role)
    {
        $role->delete();

        $this->setTotalRoleFromCache($this->getTotalRole() - 1);

        return $role;
    }
    
    /**
     * Get role permissions
     *
     * @param  Role $role
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
     * @param  Role $role
     * @param  array $permissionIds
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
     * @param  Role $role
     * @param  array $permissionIds
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

        $logData['attributes'] = [
            'permissions' => $role->permissions->pluck('name')->all(),
        ];

        $role->fillActivity($logData);
        $role->saveActivity('updated');

        $roleWithPermissions->permissions->transform(function($permission){
            return $permission->only(['id', 'name']);
        });

        return $roleWithPermissions;
    }

    /**
     * Get the total role
     *
     * @return int
     */
    public function getTotalRole()
    {   
        $totalRole = $this->getTotalRoleFromCache();
        
        if(is_null($totalRole)){
            $totalRole = $this->role->count();
            $this->setTotalRoleFromCache($totalRole);
        }

        return (int)$totalRole;
    }

    /**
     * Get the total role from cache
     *
     * @return string|null
     */
    private function getTotalRoleFromCache()
    {
        return $this->cacheService->command('HGET', ['total', 'role']);
    }

    /**
     * Set the total role from cache
     *
     * @param int $total
     * @return int
     */
    private function setTotalRoleFromCache(int $total)
    {
        return $this->cacheService->command('HSET', ['total', 'role', $total]);
    }
}