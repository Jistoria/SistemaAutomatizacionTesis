<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/redis-test', function () {
    \Illuminate\Support\Facades\Redis::set('test', 'It works!');
    return \Illuminate\Support\Facades\Redis::get('test');
});
