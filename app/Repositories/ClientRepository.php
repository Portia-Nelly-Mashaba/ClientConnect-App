<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ClientRepository
{
    /**
     * @return array<int, array{id: int, name: string, client_code: string}>
     */
    public function allSortedByName(): array
    {
        $statement = Database::connection()->query(
            'SELECT id, name, client_code FROM clients ORDER BY name ASC'
        );

        /** @var array<int, array{id: int, name: string, client_code: string}> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    }

    public function create(string $name, string $clientCode): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO clients (name, client_code) VALUES (:name, :client_code)'
        );
        $statement->execute([
            ':name' => $name,
            ':client_code' => $clientCode,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function updateClientCode(int $clientId, string $clientCode): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE clients SET client_code = :client_code WHERE id = :id'
        );
        $statement->execute([
            ':client_code' => $clientCode,
            ':id' => $clientId,
        ]);
    }

    public function clientCodeExists(string $clientCode): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT 1 FROM clients WHERE client_code = :client_code LIMIT 1'
        );
        $statement->execute([':client_code' => $clientCode]);

        return (bool) $statement->fetchColumn();
    }
}
