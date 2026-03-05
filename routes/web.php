<?php

declare(strict_types=1);

use App\Controllers\ClientController;
use App\Controllers\ContactController;
use App\Controllers\HealthController;
use App\Controllers\HomeController;
use App\Core\Router;

return static function (Router $router): void {
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/clients', [ClientController::class, 'index']);
    $router->get('/contacts', [ContactController::class, 'index']);
    $router->get('/health/db', [HealthController::class, 'db']);
};

