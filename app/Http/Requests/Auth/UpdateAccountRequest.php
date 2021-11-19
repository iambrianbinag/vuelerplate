<?php

namespace App\Http\Requests\Auth;

use App\Services\Auth\UserAuthService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{    
    /**
     * @var UserAuthService
     */
    protected $userAuthService;
    
    /**
     * UpdateAccountRequest constructor
     *
     * @param  UserAuthService $userAuthService
     * @return void
     */
    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $authUser = $this->userAuthService->getAuthUser();

        return [
            'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($authUser->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}
