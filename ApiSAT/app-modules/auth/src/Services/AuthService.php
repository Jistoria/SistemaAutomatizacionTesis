<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Events\authEvent;

class AuthService
{
    public function __construct(
        protected User $user
    )
    {}

    public function login(array $credentials) : bool|array
    {
        $user = $this->user->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }


        $token = $user->createToken('auth_token')->accessToken;
        $this->authEvent($user->name, 'Usuario logueado');
        return ['token' => $token, 'user' => $user];
    }

    public function logout(User $user) : void
    {
       $user->currentAccessToken()->delete();
    }

    public function authEvent(string $name, string $message)
    {
        event(new authEvent($name, $message));
    }
}
