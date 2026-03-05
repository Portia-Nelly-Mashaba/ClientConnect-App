<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ClientRepository
{
    /**
     * @return array<int, array{id: int, name: string, client_code: string, contacts_count: int}>
     */
    public function allSortedByName(): array
    {
        $statement = Database::connection()->query(
            'SELECT c.id, c.name, c.client_code, COUNT(cc.id) AS contacts_count
             FROM clients c
             LEFT JOIN client_contact cc ON cc.client_id = c.id
             GROUP BY c.id, c.name, c.client_code
             ORDER BY c.name ASC'
        );

        /** @var array<int, array{id: int, name: string, client_code: string, contacts_count: int}> $rows */
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

    public function exists(int $id): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT 1 FROM clients WHERE id = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        return (bool) $statement->fetchColumn();
    }

    /**
     * @return array{id: int, name: string, client_code: string}|null
     */
    public function findById(int $id): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, name, client_code FROM clients WHERE id = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        /** @var array{id: int, name: string, client_code: string} $row */
        return $row;
    }
}
