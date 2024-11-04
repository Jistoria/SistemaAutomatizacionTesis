<?php
namespace Modules\Menu\Contracts;

interface MenuServiceInterface
{
    /**
     * Obtener todos los menús basados en los IDs de roles.
     *
     * @param array $roleIds
     * @return array
     */
    public function getMenusByRolesId(array|string $roles): array;
}
