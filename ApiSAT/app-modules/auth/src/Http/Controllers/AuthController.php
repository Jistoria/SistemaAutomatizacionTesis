<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Auth\Events\authEvent;
use Modules\Auth\Services\AuthService;

class AuthController
{
    public function __construct(
        protected AuthService $authService
    )
    {}


    public function login (Request $request) : \Illuminate\Http\JsonResponse
    {
        //se piensa usar una clase request, por el momento se deja asi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Porfavor ingrese su email',
            'email.email' => 'Porfavor ingrese un email válido',
            'password.required' => 'Porfavor ingrese su contraseña',
        ]);
       try{
            $credentials = $request->only('email', 'password');
            $success = $this->authService->login($credentials);

            if ($success === false) {
                return response()->json(['success'=>false, 'message' => 'Credenciales inválidas'], 401);
            }
            return response()->json($success, 200);
       }catch (\Exception $e){
           return response()->json(['error' => $e->getMessage()], 500);
       }
    }

    public function logout(Request $request) : \Illuminate\Http\JsonResponse
    {
        try{
            $this->authService->logout($request->user());
            return response()->json(['success'=>true, 'message' => 'Sesión cerrada']);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function user(Request $request) : \Illuminate\Http\JsonResponse
    {
        try{
            $user = $this->authService->setUser($request->user());
            return response()->json($user, 200);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
