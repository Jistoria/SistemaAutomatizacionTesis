<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Modules\Menu\Contracts\MenuServiceInterface;

class UserMenuController
{
    public function __construct(
        protected MenuServiceInterface $menuService
    )
    {
    }

    public function menus(Request $request) : \Illuminate\Http\JsonResponse
    {
        try{
            $menus = $this->menuService->getMenusByRolesId($request->user()->roles->pluck('uuid')->toArray());
            return ApiResponse::success($menus,'Menus obtenidos correctamente', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
