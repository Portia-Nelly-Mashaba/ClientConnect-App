<?php

declare(strict_types=1);

use App\Controllers\ClientController;
use App\Controllers\ClientContactController;
use App\Controllers\ContactController;
use App\Controllers\HealthController;
use App\Core\Router;

return static function (Router $router): void {
    $router->get('/', static function (): void {
        header('Location: /clients');
        http_response_code(302);
    });
    $router->get('/clients', [ClientController::class, 'index']);
    $router->post('/clients', [ClientController::class, 'store']);
    $router->get('/clients/{clientId}', [ClientController::class, 'show']);
    $router->get('/contacts', [ContactController::class, 'index']);
    $router->post('/contacts', [ContactController::class, 'store']);
    $router->get('/contacts/{contactId}', [ContactController::class, 'show']);
    $router->post('/clients/{clientId}/contacts', [ClientContactController::class, 'linkContactToClient']);
    $router->post('/clients/{clientId}/contacts/{contactId}/unlink', [ClientContactController::class, 'unlinkContactFromClient']);
    $router->post('/contacts/{contactId}/clients', [ClientContactController::class, 'linkClientToContact']);
    $router->post('/contacts/{contactId}/clients/{clientId}/unlink', [ClientContactController::class, 'unlinkClientFromContact']);
    $router->get('/health/db', [HealthController::class, 'db']);
};

