<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{    
    /**
     * Get all users
     *
     * @param GetUsersRequest $request
     * @return void
     */
    public function index(GetUsersRequest $request)
    {
        $perPage = $request->per_page ?? config('settings.pagination.per_page');
        $orderBy = $request->order_by;
        $orderDirection = $request->order_direction ?? config('settings.pagination.order_direction');
        $search = $request->search;

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

        return response()->json($users);
    }
    
    /**
     * Create a user
     *
     * @param CreateUserRequest $request
     * @return void
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $user = User::create($data);

        return response()->json($user);
    }
    
    /**
     * Show a user
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json($user);
    }
        
    /**
     * Update a user
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name', 'email', 'password']);
        if($password = isset($data['password'])){
            $data['password'] = Hash::make($password);
        }

        $user->update($data);

        return response()->json($user);
    }
    
    /**
     * Delete a user
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json($user);
    }
}
