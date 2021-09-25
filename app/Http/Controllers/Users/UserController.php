<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $data = $request->all();
        $user = $this->userService->createUser($data);

        return new UserResource($user);
    }
    
    /**
     * Show a user
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = $this->userService->getUser($id);

        return new UserResource($user);
    }
        
    /**
     * Update a user
     *
     * @param UpdateUserRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->getUser($id);

        $data = $request->all();  

        $user = $this->userService->updateUser($user, $data);

        return new UserResource($user);
    }
    
    /**
     * Delete a user
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = $this->userService->getUser($id);
        $this->userService->deleteUser($user);

        return new UserResource($user);
    }
    
    /**
     * Get the total user
     *
     * @return JsonResponse
     */
    public function getTotalUser()
    {
        $userTotal = $this->userService->getTotalUser();

        return response()->json([
            'total' => $userTotal
        ]);
    }
}
