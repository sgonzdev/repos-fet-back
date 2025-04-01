<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */

    protected function successResponse($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */

    protected function errorResponse(string $message, int $code, array $errors = [])
    {
        $response = [
            'status' => 'error',
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * @param string $token
     * @param int $ttl
     * @return \Illuminate\Http\JsonResponse
     */

    protected function tokenResponse(string $token, int $ttl)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60,
        ]);
    }
}
