<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\Database;
use Throwable;

final class HealthController
{
    public function db(): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        try {
            Database::connection()->query('SELECT 1');
            echo json_encode([
                'status' => 'ok',
                'database' => (string) Config::get('database.database', 'unknown'),
            ], JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], JSON_THROW_ON_ERROR);
        }
    }
}
