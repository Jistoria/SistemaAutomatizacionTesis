<?php

namespace Modules\Menu\Services;

use Modules\Menu\Models\MenusUser;
use Modules\Menu\Contracts\MenuServiceInterface;
class MenuAuthService implements MenuServiceInterface
{
    public function __construct(
        protected MenusUser $menusUser
    )
    {}

     /**
     * Recupera menús basados en roles.
     *
     * @param array|string $roles Los roles para filtrar los menús.
     * @return array La lista de menús asociados con los roles dados.
     */
    public function getMenusByRolesId(array|string $roles): array
    {
        $menus = $this->menusUser->getMenusByRolesId($roles);
        return $menus;
    }
}
