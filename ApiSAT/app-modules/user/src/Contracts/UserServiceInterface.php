<?php
namespace Modules\User\Contracts;

use App\Models\Auth\User;

interface UserServiceInterface
{
    /**
     * Crea un usuario.
     *
     * @param array $data Los datos del usuario.
     * @return User El usuario creado/encontrado.
     */
    public function createUserWithRole(array $data, string|array $role, string $userId): User;
}
