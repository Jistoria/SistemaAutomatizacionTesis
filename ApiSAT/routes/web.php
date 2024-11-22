<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/redis-test', function () {
    \Illuminate\Support\Facades\Redis::set('test', 'It works!');
    return \Illuminate\Support\Facades\Redis::get('test');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});
Route::get('/diagnostico', function () {
    // Obtener información del sistema y servicios
    $phpFpmStatus = shell_exec('ps aux | grep php-fpm');
    $nginxStatus = shell_exec('ps aux | grep nginx');

    // Leer logs de PHP-FPM y Nginx
    $phpFpmLogs = file_exists('/var/log/php-fpm.err.log') ? file_get_contents('/var/log/php-fpm.err.log') : 'Log de PHP-FPM no encontrado';
    $nginxLogs = file_exists('/var/log/nginx.err.log') ? file_get_contents('/var/log/nginx.err.log') : 'Log de Nginx no encontrado';

    // Revisar permisos de algunos directorios
    $permissions = [
        'var_www' => substr(sprintf('%o', fileperms('/var/www')), -4),
        'var_log_php' => substr(sprintf('%o', fileperms('/var/log/php-fpm.err.log')), -4),
        'var_log_nginx' => substr(sprintf('%o', fileperms('/var/log/nginx.err.log')), -4),
    ];

    // Mostrar la información
    return response()->json([
        'php_fpm_status' => $phpFpmStatus,
        'nginx_status' => $nginxStatus,
        'php_fpm_logs' => $phpFpmLogs,
        'nginx_logs' => $nginxLogs,
        'permissions' => $permissions,
    ]);
});
