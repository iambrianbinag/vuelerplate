<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Services\Auth\Exceptions\InvalidCredentialsException;
use App\Services\Auth\UserAuthService;
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
     * @param UserAuthService $userAuthService
     */
    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $authUser = $this->userAuthService->getAuthUser();

        return response()->json($authUser);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $logoutMessage = $this->userAuthService->logout();

        return response()->json($logoutMessage);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = $this->userAuthService->refresh();

        return response()->json($token);
    }
}
