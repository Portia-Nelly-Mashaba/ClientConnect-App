<?php

declare(strict_types=1);

// Usage: php database/migrate.php
// Applies the SQL schema to the configured MySQL server.
$config = require __DIR__ . '/../config/database.php';

$dsn = sprintf(
    'mysql:host=%s;port=%d;charset=%s',
    $config['host'],
    (int) $config['port'],
    $config['charset']
);

$pdo = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$sql = file_get_contents(__DIR__ . '/migrations/000_schema.sql');
if ($sql === false) {
    throw new RuntimeException('Could not read migration SQL file.');
}

$pdo->exec($sql);
echo "Migration applied successfully.\n";
