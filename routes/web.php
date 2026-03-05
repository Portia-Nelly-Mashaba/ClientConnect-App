<?php

declare(strict_types=1);

use App\Controllers\ClientController;
use App\Controllers\ClientContactController;
use App\Controllers\ContactController;
use App\Controllers\HealthController;
use App\Controllers\HomeController;
use App\Core\Router;

return static function (Router $router): void {
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/clients', [ClientController::class, 'index']);
    $router->post('/clients', [ClientController::class, 'store']);
    $router->get('/contacts', [ContactController::class, 'index']);
    $router->post('/contacts', [ContactController::class, 'store']);
    $router->post('/client-contacts/link', [ClientContactController::class, 'link']);
    $router->post('/client-contacts/unlink', [ClientContactController::class, 'unlink']);
    $router->get('/health/db', [HealthController::class, 'db']);
};

