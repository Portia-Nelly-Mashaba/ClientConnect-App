<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ContactRepository
{
    /**
     * @return array<int, array{id: int, name: string, surname: string, email: string}>
     */
    public function allSortedBySurnameAndName(): array
    {
        $statement = Database::connection()->query(
            'SELECT id, name, surname, email FROM contacts ORDER BY surname ASC, name ASC'
        );

        /** @var array<int, array{id: int, name: string, surname: string, email: string}> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    }
}
