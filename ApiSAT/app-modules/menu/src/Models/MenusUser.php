<?php

namespace Modules\Menu\Models;

use App\Models\General\Menu;
use Illuminate\Database\Eloquent\Model;

class MenusUser extends Menu
{
    public static function getMenusByRolesId(array|string $roles): array
    {
        // Seleccionar solo los campos necesarios y asegurarse de que no haya duplicados
        $menus = self::select('menu_id', 'name', 'url', 'icon')
        ->distinct()
        ->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('uuid', (array) $roles);
        })
        ->get()
        ->toArray();


        return $menus;
    }
}
