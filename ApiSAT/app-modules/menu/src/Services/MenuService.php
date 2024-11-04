<?php

namespace Modules\Menu\Services;

use App\Models\General\Menu;
use Modules\Menu\Models\MenusUser;
/**
 * Clase MenuService
 *
 * Este servicio maneja operaciones relacionadas con menús.
 *
 * @package App\Modules\Menu\Services
 */
class MenuService
{
    /**
     * Constructor de MenuService.
     *
     * @param Menu $menu La instancia del menú.
     * @param MenusUser $menusUser La instancia del usuario de menús.
     */
    public function __construct(
        protected Menu $menu,
        protected MenusUser $menusUser
    ) {}


}
