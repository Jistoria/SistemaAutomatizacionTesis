<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MicrosoftAuthController;

Route::get('/microsoft/token', [MicrosoftAuthController::class, 'getToken']);

Route::post('/microsoft/login', [MicrosoftAuthController::class, 'authenticatedMS']);
