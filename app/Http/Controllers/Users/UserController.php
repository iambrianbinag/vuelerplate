<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Users\User;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{        
    /**
     * @var UserService
     */
    protected $userService;
    
    /**
     * UserController constructor
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get all users
     *
     * @param GetUsersRequest $request
     * @return JsonResponse
     */
    public function index(GetUsersRequest $request)
    {
        $users = $this->userService->getUsers(
            $request->search,
            $request->per_page,
            $request->order_by,
            $request->order_direction
        );

        return response()->json($users);
    }
    
    /**
     * Create a user
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        if($password = isset($data['password'])){
            $data['password'] = Hash::make($password);
        }
        
        $user = User::create($data);
        $user->assignRole($request->role_id);

        $data['role'] = $user->role['name'];
        $logData = ['attributes' => collect($data)->except(['password'])->all()];
        $user->fillActivity($logData);
        $user->saveActivity('created');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }
    
    /**
     * Show a user
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]);
    }
        
    /**
     * Update a user
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $logData = [
            'attributes' => [], 
            'old' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role['name']
            ],
        ];

        $data = $request->only(['name', 'email', 'password']);
        if($password = isset($data['password'])){
            $data['password'] = Hash::make($password);
        }

        $user->update($data);
        $user->syncRoles($request->role_id);

        $logData['attributes'] = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role['name']
        ];

        $user->fillActivity($logData);
        $user->saveActivity('updated');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }
    
    /**
     * Delete a user
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
