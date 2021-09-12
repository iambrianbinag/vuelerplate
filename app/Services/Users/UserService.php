<?php

namespace App\Services\Users;

use App\Models\Users\User;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

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

        $users = $this->user::select('id', 'name', 'email')
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
    
    /**
     * Create a user
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data)
    {
        $dataWithRoleId = $data;
        $dataWithoutRoleId = collect($dataWithRoleId)->only(['name', 'email', 'password'])->toArray();
        
        if($password = isset($dataWithoutRoleId['password'])){
            $dataWithoutRoleId['password'] = Hash::make($password);
        }
        
        $user = $this->user::create($dataWithoutRoleId);
        $user->assignRole($dataWithRoleId['role_id']);

        $dataWithoutRoleId['role'] = $user->role['name'];
        $logData = ['attributes' => collect($dataWithoutRoleId)->except(['password'])->all()];
        $user->fillActivity($logData);
        $user->saveActivity('created');

        return $user;
    }
    
    /**
     * Get a user
     *
     * @param $id
     * @return User
     */
    public function getUser($id)
    {
        $user = $this->user::findOrFail($id);

        return $user;
    }
    
    /**
     * Update a user
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, array $data)
    {
        $logData = [
            'attributes' => [], 
            'old' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role['name']
            ],
        ];

        $dataWithRoleId = $data;
        $dataWithoutRoleId = collect($dataWithRoleId)->only(['name', 'email', 'password'])->toArray();

        if($password = isset($dataWithoutRoleId['password'])){
            $dataWithoutRoleId['password'] = Hash::make($password);
        }

        $user->update($dataWithoutRoleId);
        $user->syncRoles($dataWithRoleId['role_id']);

        $logData['attributes'] = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role['name']
        ];

        $user->fillActivity($logData);
        $user->saveActivity('updated');

        return $user;
    }
}