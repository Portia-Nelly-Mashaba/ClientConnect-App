<?php

declare(strict_types=1);

// Usage: php database/seed.php
// Seeds sample clients, contacts, and client-contact links.
$config = require __DIR__ . '/../config/database.php';

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $config['host'],
    (int) $config['port'],
    $config['database'],
    $config['charset']
);

$pdo = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

try {
    // Reset data in FK-safe order.
    $pdo->exec('DELETE FROM client_contact');
    $pdo->exec('DELETE FROM contacts');
    $pdo->exec('DELETE FROM clients');
    $pdo->exec('ALTER TABLE client_contact AUTO_INCREMENT = 1');
    $pdo->exec('ALTER TABLE contacts AUTO_INCREMENT = 1');
    $pdo->exec('ALTER TABLE clients AUTO_INCREMENT = 1');

    $clients = [
        ['First National Bank', 'FNB001'],
        ['Protea Holdings', 'PRO123'],
        ['IT Alpha Group', 'ITA001'],
    ];

    $clientStmt = $pdo->prepare('INSERT INTO clients (name, client_code) VALUES (:name, :client_code)');
    foreach ($clients as [$name, $code]) {
        $clientStmt->execute([
            ':name' => $name,
            ':client_code' => $code,
        ]);
    }

    $contacts = [
        ['Portia', 'Mashaba', 'portia@example.com'],
        ['Lerato', 'Mokoena', 'lerato@example.com'],
        ['Sipho', 'Ndlovu', 'sipho@example.com'],
        ['Anele', 'Khumalo', 'anele@example.com'],
    ];

    $contactStmt = $pdo->prepare('INSERT INTO contacts (name, surname, email) VALUES (:name, :surname, :email)');
    foreach ($contacts as [$name, $surname, $email]) {
        $contactStmt->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':email' => $email,
        ]);
    }

    $links = [
        [1, 1],
        [1, 2],
        [2, 2],
        [2, 3],
        [3, 4],
    ];

    $linkStmt = $pdo->prepare('INSERT INTO client_contact (client_id, contact_id) VALUES (:client_id, :contact_id)');
    foreach ($links as [$clientId, $contactId]) {
        $linkStmt->execute([
            ':client_id' => $clientId,
            ':contact_id' => $contactId,
        ]);
    }

    echo "Seed completed successfully.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, "Seed failed: " . $exception->getMessage() . PHP_EOL);
    exit(1);
}
