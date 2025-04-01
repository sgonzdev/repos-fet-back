<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\auth\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @param AuthService $authService
     * @return void
     */

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->authService = $authService;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        try {
            $credentials = $this->authService->validateLoginCredentials($request->all());
            $token = $this->authService->attemptLogin($credentials);

            if (!$token) {
                return $this->errorResponse('Unauthorized', 401);
            }

            return $this->tokenResponse(
                $token,
                auth('api')->factory()->getTTL()
            );

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation error', 422, $e->errors());
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function me()
    {
        $userInfo = $this->authService->getUserInfo();
        return $this->successResponse($userInfo);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout()
    {
        $this->authService->logout();
        return $this->successResponse(null, 'Successfully logged out');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh()
    {
        $token = $this->authService->refreshToken();
        return $this->tokenResponse(
            $token,
            auth('api')->factory()->getTTL()
        );
    }
}
