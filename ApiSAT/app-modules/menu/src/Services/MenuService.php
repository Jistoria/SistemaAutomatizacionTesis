<?php

namespace Modules\Menu\Services;

use App\Models\General\Menu;
use Modules\Menu\Contracts\MenuServiceInterface;
use Modules\Menu\Models\MenusUser;

class MenuService implements MenuServiceInterface
{
    public function __construct(
        protected Menu $menu,
        protected MenusUser $menusUser
    )
    {}

    public function getMenusByRolesId(array|string $roles): array
    {
        $menus = $this->menusUser->getMenusByRolesId($roles);
        return $menus;
    }


}
