<?php

namespace App\Services\Auth;

use App\Services\Auth\Exceptions\InvalidCredentialsException;

class UserAuthService
{    
    /**
     * Get a JWT via given credentials.
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login(string $email, string $password)
    {
        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        if(!$token = auth()->attempt($credentials)){
            throw new InvalidCredentialsException('Invalid email or password');
        }

        return $this->respondWithToken($token);
    }
    
    /**
     * Get the authenticated User.
     *
     * @return array
     */
    public function getAuthUser()
    {
        $authUser = auth()->user();

        return [
            'id' => $authUser->id,
            'name' => $authUser->name,
            'email' => $authUser->email,
        ];
    }
    
    /**
     * Log the user out (Invalidate the token).
     *
     * @return array
     */
    public function logout()
    {
        auth()->logout();

        return [
            'message' => 'Successfully logged out'
        ];
    }
    
    /**
     * Refresh a token
     *
     * @return array
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    
    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return array
     */
    private function respondWithToken(string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}