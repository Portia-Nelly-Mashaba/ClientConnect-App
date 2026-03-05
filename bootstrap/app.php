<?php

declare(strict_types=1);

use App\Core\Router;

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

$composerAutoload = BASE_PATH . '/vendor/autoload.php';
if (is_file($composerAutoload)) {
    require_once $composerAutoload;
} else {
    spl_autoload_register(static function (string $class): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    });
}

$router = new Router();
$registerRoutes = require BASE_PATH . '/routes/web.php';
$registerRoutes($router);

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$router->dispatch($requestMethod, $requestUri);

