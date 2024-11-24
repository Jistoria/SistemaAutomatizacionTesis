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
    $gnixStatus = shell_exec('service nginx status');
    $gnixT = shell_exec('gnix -t');

    return response()->json([
        'status' => $gnixStatus,
        'test' => $gnixT,
    ]);
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

});

Route::get('/logs/{service}', function ($service) {
    $logs = [
        'nginx' => '/var/log/nginx.err.log',
        'php-fpm' => '/var/log/php-fpm.err.log',
        'queue' => '/dev/fd/1',
        'reverb' => '/var/www/storage/logs/reverb.err.log',
    ];

    if (!isset($logs[$service])) {
        return response()->json(['error' => 'Log file not found for service: ' . $service], 404);
    }

    $file = $logs[$service];
    if (file_exists($file)) {
        return response()->file($file);
    }

    return response()->json(['error' => 'Log file does not exist: ' . $file], 404);
});


Route::get('/check-ssl', function () {
    $host = request()->getHost(); // Obtén el host actual (e.g., tu-dominio.com)
    $port = 443; // Puerto estándar para HTTPS
    $context = stream_context_create([
        "ssl" => [
            "capture_peer_cert" => true,
        ],
    ]);

    try {
        // Abre una conexión al host con SSL
        $client = stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

        if (!$client) {
            return response()->json(['error' => "Failed to connect: $errstr ($errno)"], 500);
        }

        // Obtén los detalles del certificado
        $params = stream_context_get_params($client);
        $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);

        return response()->json([
            'valid_from' => date('Y-m-d H:i:s', $cert['validFrom_time_t']),
            'valid_to' => date('Y-m-d H:i:s', $cert['validTo_time_t']),
            'issuer' => $cert['issuer'],
            'subject' => $cert['subject'],
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
