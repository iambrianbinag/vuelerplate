<?php

namespace App\Services\Users;

use App\Models\Users\User;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserService extends Service
{    
    /**
     * @var User
     */
    protected $user;
    
    /**
     * UserService constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
    }
    
    /**
     * Get users
     *
     * @param string $search
     * @param int $perPage
     * @param string $orderBy
     * @param string $orderDirection
     * @return LengthAwarePaginator|Collection
     */
    public function getUsers(
        ?string $search,
        ?int $perPage,
        ?string $orderBy,
        ?string $orderDirection
    )
    {
        $perPage = $perPage ?? $this->paginationPerPageDefault;
        $orderDirection = $orderDirection ?? $this->paginationOrderDirectionDefault;

        $users = User::select('id', 'name', 'email')
            ->when($search, function($query, $search){
                return $query->where(function($query) use ($search){
                    $query->where('id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->when($orderBy, function($query, $orderBy) use ($orderDirection){
                return $query->orderBy($orderBy, $orderDirection);   
            }, function($query) use ($orderDirection){
                return $query->orderBy('id', $orderDirection);
            })
            ->paginate($perPage);

        return $users;
    }
}