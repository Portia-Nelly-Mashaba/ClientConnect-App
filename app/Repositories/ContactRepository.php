<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ContactRepository
{
    /**
     * @return array<int, array{id: int, name: string, surname: string, email: string, clients_count: int}>
     */
    public function allSortedBySurnameAndName(): array
    {
        $statement = Database::connection()->query(
            'SELECT ct.id, ct.name, ct.surname, ct.email, COUNT(cc.id) AS clients_count
             FROM contacts ct
             LEFT JOIN client_contact cc ON cc.contact_id = ct.id
             GROUP BY ct.id, ct.name, ct.surname, ct.email
             ORDER BY ct.surname ASC, ct.name ASC'
        );

        /** @var array<int, array{id: int, name: string, surname: string, email: string, clients_count: int}> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    }

    public function create(string $name, string $surname, string $email): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO contacts (name, surname, email) VALUES (:name, :surname, :email)'
        );
        $statement->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':email' => $email,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function emailExists(string $email): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT 1 FROM contacts WHERE email = :email LIMIT 1'
        );
        $statement->execute([':email' => strtolower($email)]);

        return (bool) $statement->fetchColumn();
    }

    public function exists(int $id): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT 1 FROM contacts WHERE id = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);

        return (bool) $statement->fetchColumn();
    }

    /**
     * @return array{id: int, name: string, surname: string, email: string}|null
     */
    public function findById(int $id): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, name, surname, email FROM contacts WHERE id = :id LIMIT 1'
        );
        $statement->execute([':id' => $id]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        /** @var array{id: int, name: string, surname: string, email: string} $row */
        return $row;
    }
}
