<?php

use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Artisan;

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


Route::get('/check-php', function () {
    return 'PHP-FPM is running.';
});

Route::get('/check-nginx', function () {
    return 'Nginx is serving the application.';
});

Route::get('/check-queue', function () {
    try {
        Artisan::call('queue:work --stop-when-empty');
        return 'Queue worker command executed successfully.';
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/check-reverb', function () {
    $host = 'http://127.0.0.1:8080';
    $curl = curl_init($host);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 3);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($httpCode == 200) {
        return 'Reverb service is reachable.';
    }

    return response()->json(['error' => 'Reverb service is not responding'], $httpCode);
});

