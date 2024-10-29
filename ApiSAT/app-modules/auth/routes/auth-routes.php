<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::prefix('auth')->group(
    function () {
        Route::post('login', [AuthController::class, 'login'])->middleware(['guest:api', 'throttle:6,1']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
    }
);
