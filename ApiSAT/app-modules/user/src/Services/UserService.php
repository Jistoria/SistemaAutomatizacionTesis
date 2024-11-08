<?php

namespace Modules\User\Services;

use App\Models\Auth\User;

class UserService
{
    public function __construct(
        protected User $user
    )
    {}

    /**
     * Crea un usuario.
     *
     * @param array $data Los datos del usuario.
     * @return User El usuario creado/encontrado.
     */
    public function createUserWithRole(array $data, string|array $role, string $userId): User
    {
        $user= $this->user->firstOrCreate(['name' => $data['name']], [
            'email' => $data['email'],
            'password' => $data['password'],
            'created_by_user' => $userId,
            'updated_by_user' => $userId,
        ]);
        $user->assignRole($role);
        return $user;
    }
}
