<?php

namespace Modules\AnalystDegree\Services;

use App\Models\Auth\User;

interface AuthServiceInterface
{
    public function login(array $credentials) : bool|array;

    public function logout(User $user) : void;

    public function loginMS(array $credentials) : bool|array;

}
