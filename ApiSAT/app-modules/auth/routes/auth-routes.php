<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\UserMenuController;

Route::prefix('auth')->group(
    function () {
        Route::post('login', [AuthController::class, 'login'])->middleware(['guest:api'])->name('auth.login');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');
        Route::get('user', [AuthController::class, 'user'])->middleware('auth:api')->name('auth.user');
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('auth.refresh');
        Route::get('menus', [UserMenuController::class, 'menus'])->middleware('auth:api')->name('auth.menus');
    }
);
