<?php

namespace App\Services\Auth;

use App\Models\Users\User;
use App\Services\Auth\Exceptions\InvalidCredentialsException;
use App\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserAuthService extends Service
{    
    /**
     * Get a JWT via given credentials.
     *
     * @param  string $email
     * @param  string $password
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
     * @return Model
     */
    public function getAuthUser()
    {
        return auth()->user();
    }
    
    /**
     * Update user account
     *
     * @param  User $user
     * @param  array $data
     * @return User
     */
    public function updateMyAccount(User $user, array $data)
    {
        $logData = [
            'attributes' => [], 
            'old' => [
                'email' => $user->email,
            ],
        ];

        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        $logData['attributes'] = [
            'email' => $user->email,
        ];

        $user->fillActivity($logData);
        $user->saveActivity('updated');

        return $user;
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
     * @param  string $token
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