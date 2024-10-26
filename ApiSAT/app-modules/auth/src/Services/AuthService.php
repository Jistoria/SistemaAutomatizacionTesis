<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected User $user
    )
    {}

    public function login(array $credentials) : bool|string
    {
        $user = $this->user->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        $token = $user->createToken('auth_token')->accessToken;

        return $token;
    }

    public function logout(User $user) : void
    {
       $user->currentAccessToken()->delete();
    }
}
