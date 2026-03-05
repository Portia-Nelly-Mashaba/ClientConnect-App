<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        /** @var array<string, string|int> $db */
        $db = Config::get('database', []);
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            (string) ($db['host'] ?? '127.0.0.1'),
            (string) ($db['port'] ?? 3306),
            (string) ($db['database'] ?? ''),
            (string) ($db['charset'] ?? 'utf8mb4')
        );

        try {
            self::$connection = new PDO(
                $dsn,
                (string) ($db['username'] ?? ''),
                (string) ($db['password'] ?? ''),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed: ' . $exception->getMessage(), 0, $exception);
        }

        return self::$connection;
    }
}
