<?php

namespace App\Services\Permissions;

use App\Models\Permissions\Permission;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Services\Cache\Interfaces\CacheInterface;

class PermissionService extends Service
{    
    /**
     * @var mixed
     */
    protected $permission;
    
    /**
     * @var CacheInterface
     */
    protected $cacheService;
    
    /**
     * PermissionService constructor
     * 
     * @param  Permission $permission
     * @param  CacheInterface $cacheService
     */
    public function __construct(Permission $permission, CacheInterface $cacheService)
    {
        parent::__construct();

        $this->permission = $permission;

        $this->cacheService = $cacheService;
    }
    
    /**
     * Get permissions
     *
     * @param  string $search
     * @param  int $perPage
     * @param  string $orderBy
     * @param  string $orderDirection
     * @param  bool $notPaginated
     * @param  int $queryChunk
     * @return LengthAwarePaginator|Collection
     */
    public function getPermissions(
        ?string $search,
        ?int $perPage,
        ?string $orderBy,
        ?string $orderDirection,
        ?bool $notPaginated,
        ?int $queryChunk
    )
    {
        $perPage = $perPage ?? $this->paginationPerPageDefault;
        $orderDirection = $orderDirection ?? $this->paginationOrderDirectionDefault;
        $queryChunk = $queryChunk ?? $this->queryChunkDefault;

        $permissions = $this->permission::select('id', 'name', 'order')
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
            ->when($notPaginated, function($query) use ($queryChunk){
                $permissionsChunked = [];
                $query->chunk($queryChunk, function($permissions) use (&$permissionsChunked){
                    foreach($permissions as $permission){
                        array_push($permissionsChunked, $permission);
                    }
                });

                return collect($permissionsChunked);
            }, function($query) use ($perPage){
                return $query->paginate($perPage);
            });

        return $permissions;
    }
    
    /**
     * Create a permission
     *
     * @param  array $data
     * @return Permission
     */
    public function createPermission(array $data)
    {
        $permission = $this->permission::create($data);
        $this->setTotalPermissionFromCache($this->getTotalPermission() + 1);

        return $permission;
    }
        
    /**
     * Get a permission
     *
     * @param  $id
     * @return Permission
     */
    public function getPermission($id)
    {
        return $this->permission::findOrFail($id);
    }
    
    /**
     * Update a permission
     *
     * @param  Permission $permission
     * @param  array $data
     * @return Permission
     */
    public function updatePermission(Permission $permission, array $data)
    {
        $permission->update($data);

        return $permission;
    }
    
    /**
     * Delete a permission
     *
     * @param  Permission $permission
     * @return Permission
     */
    public function deletePermission(Permission $permission)
    {
        $permission->delete();

        $this->setTotalPermissionFromCache($this->getTotalPermission() - 1);

        return $permission;
    }
    
    /**
     * Get the total permission
     *
     * @return int
     */
    public function getTotalPermission()
    {
        $totalPermission = $this->getTotalPermissionFromCache();

        if(is_null($totalPermission)){
            $totalPermission = $this->permission->count();
            $this->setTotalPermissionFromCache($totalPermission);
        }

        return (int)$totalPermission;
    }
    
    /**
     * Get the total permission from cache
     *
     * @return string|null
     */
    private function getTotalPermissionFromCache()
    {
        return $this->cacheService->command('HGET', ['total', 'permission']);
    }
    
    /**
     * Set the total permission from cache
     *
     * @param  int $total
     * @return int
     */
    private function setTotalPermissionFromCache(int $total)
    {
        return $this->cacheService->command('HSET', ['total', 'permission', $total]);
    }
}