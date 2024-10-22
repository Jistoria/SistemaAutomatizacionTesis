<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login'])->middleware('guest:api');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
