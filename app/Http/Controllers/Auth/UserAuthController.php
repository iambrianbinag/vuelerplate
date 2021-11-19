<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\UpdateAccountRequest;
use App\Http\Resources\Users\UserResource;
use App\Services\Auth\Exceptions\InvalidCredentialsException;
use App\Services\Auth\UserAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{        
    /**
     * @var UserAuthService
     */
    protected $userAuthService;
    
    /**
     * UserAuthController constructor
     *
     * @param  UserAuthService $userAuthService
     */
    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $token = $this->userAuthService->login($request->email, $request->password);

            return response()->json($token);
        } catch (InvalidCredentialsException $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        $authUser = $this->userAuthService->getAuthUser();

        return new UserResource($authUser);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $logoutMessage = $this->userAuthService->logout();

        return response()->json($logoutMessage);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        $token = $this->userAuthService->refresh();

        return response()->json($token);
    }
    
    /**
     * Update account of authenticated user
     *
     * @return JsonResponse
     */
    public function updateMyAcount(UpdateAccountRequest $request)
    {
        $data = $request->only(['email', 'password']);

        $authUser = $this->userAuthService->getAuthUser();

        $updatedUserAccount = $this->userAuthService->updateMyAccount($authUser, $data);

        return response()->json([
            'id' => $updatedUserAccount->id,
            'email' => $updatedUserAccount->email,
        ]);
    }
}
