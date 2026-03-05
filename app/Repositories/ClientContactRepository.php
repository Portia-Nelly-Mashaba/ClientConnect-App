<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ClientContactRepository
{
    public function isLinked(int $clientId, int $contactId): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT 1 FROM client_contact WHERE client_id = :client_id AND contact_id = :contact_id LIMIT 1'
        );
        $statement->execute([
            ':client_id' => $clientId,
            ':contact_id' => $contactId,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function link(int $clientId, int $contactId): bool
    {
        if ($this->isLinked($clientId, $contactId)) {
            return false;
        }

        $statement = Database::connection()->prepare(
            'INSERT INTO client_contact (client_id, contact_id) VALUES (:client_id, :contact_id)'
        );

        return $statement->execute([
            ':client_id' => $clientId,
            ':contact_id' => $contactId,
        ]);
    }

    public function unlink(int $clientId, int $contactId): bool
    {
        $statement = Database::connection()->prepare(
            'DELETE FROM client_contact WHERE client_id = :client_id AND contact_id = :contact_id'
        );
        $statement->execute([
            ':client_id' => $clientId,
            ':contact_id' => $contactId,
        ]);

        return $statement->rowCount() > 0;
    }

    /**
     * @return array<int, array{client_id: int, client_name: string, contact_id: int, contact_name: string, contact_surname: string}>
     */
    public function allLinks(): array
    {
        $statement = Database::connection()->query(
            'SELECT cc.client_id, c.name AS client_name, cc.contact_id, ct.name AS contact_name, ct.surname AS contact_surname
             FROM client_contact cc
             INNER JOIN clients c ON c.id = cc.client_id
             INNER JOIN contacts ct ON ct.id = cc.contact_id
             ORDER BY c.name ASC, ct.surname ASC, ct.name ASC'
        );

        /** @var array<int, array{client_id: int, client_name: string, contact_id: int, contact_name: string, contact_surname: string}> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    }
}
