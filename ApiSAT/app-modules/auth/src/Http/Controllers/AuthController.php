<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Auth\Services\AuthService;

class AuthController
{
    public function __construct(
        protected AuthService $authService
    )
    {}


    public function login (Request $request)
    {
       try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Porfavor ingrese su email',
                'email.email' => 'Porfavor ingrese un email válido',
                'password.required' => 'Porfavor ingrese su contraseña',
            ]);
            $credentials = $request->only('email', 'password');
            $token = $this->authService->login($credentials);

            if ($token === false) {
                return response()->json(['message' => 'Credenciales inválidas'], 401);
            }

            return response()->json(['token' => $token]);
       }catch (\Exception $e){
           return response()->json(['error' => $e->getMessage()], 400);
       }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
