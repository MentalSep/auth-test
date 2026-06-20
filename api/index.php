<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

register_shutdown_function(function (): void {
    if ($error = error_get_last()) {
        error_log('BOOTSTRAP_FATAL: '.json_encode($error));
    }
});

$runtime = '/tmp/laravel';
$storage = "$runtime/storage";

foreach (['bootstrap/cache', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs'] as $directory) {
    is_dir("$runtime/$directory") || mkdir("$runtime/$directory", 0777, true);
}

foreach ([
    'APP_CONFIG_CACHE' => "$runtime/bootstrap/cache/config.php",
    'APP_EVENTS_CACHE' => "$runtime/bootstrap/cache/events.php",
    'APP_PACKAGES_CACHE' => "$runtime/bootstrap/cache/packages.php",
    'APP_ROUTES_CACHE' => "$runtime/bootstrap/cache/routes.php",
    'APP_SERVICES_CACHE' => "$runtime/bootstrap/cache/services.php",
] as $name => $path) {
    putenv("$name=$path");
    $_ENV[$name] = $_SERVER[$name] = $path;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($storage);

try {
    $app->handleRequest(Request::capture());
} catch (Throwable $e) {
    error_log('BOOTSTRAP_EXCEPTION: '.$e);
    http_response_code(500);
    echo 'Application bootstrap failed. Check BOOTSTRAP_EXCEPTION in the function logs.';
}
