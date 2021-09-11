<?php

namespace App\Services\Permissions;

use App\Models\Permissions\Permission;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PermissionService extends Service
{    
    /**
     * @var mixed
     */
    protected $permission;
    
    /**
     * PermissionService constructor
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();

        $this->permission = $permission;
    }
    
    /**
     * Get permissions
     *
     * @param string $search
     * @param int $perPage
     * @param string $orderBy
     * @param string $orderDirection
     * @param bool $notPaginated
     * @param int $queryChunk
     * 
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
     * @param array $data
     * @return Permission
     */
    public function createPermission(array $data)
    {
        $permission = $this->permission::create($data);

        return $permission;
    }
    
}