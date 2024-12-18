<?php

namespace Modules\Menu\Models;

use App\Models\General\Menu;
use Illuminate\Database\Eloquent\Model;

class MenusUser extends Menu
{
    public static function getMenusByRolesId(array|string $roles): array
    {
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
