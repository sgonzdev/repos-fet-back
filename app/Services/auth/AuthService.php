<?php

namespace App\Services\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * @param array $data
     * @return array
     * @throws ValidationException
     */

    public function validateLoginCredentials(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return $validator->validated();
    }

    /**
     * @param array $credentials
     * @return string|null
     */

    public function attemptLogin(array $credentials)
    {
        return auth('api')->attempt($credentials);
    }

    /**
     * @return array
     */

    public function getUserInfo()
    {
        $user = auth()->user();
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }

    /**
     * @return bool
     */

    public function logout()
    {
        return auth()->logout();
    }

    /**
     * @return string
     */

    public function refreshToken()
    {
        return auth()->refresh();
    }

    /**
     * @param string $token
     * @return array
     */

    public function generateTokenResponse(string $token)
    {
        return [
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $code
     * @return array
     */

    public function generateErrorResponse(string $message, array $errors = [], int $code = 400)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'code' => $code
        ];
    }
}
