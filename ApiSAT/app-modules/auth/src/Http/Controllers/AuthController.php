<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\Auth\Events\authEvent;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Services\AuthService;

class AuthController
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $data = $this->authService->login($credentials);

            if ($data === false) {
                return ApiResponse::error('Credenciales inválidas', 401);
            }

            return ApiResponse::success($data, 'Sesión Iniciada', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return ApiResponse::success(null, 'Sesión cerrada', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function user(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->authService->setUser($request->user());
            return ApiResponse::success($user, 'Datos Usuario', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
