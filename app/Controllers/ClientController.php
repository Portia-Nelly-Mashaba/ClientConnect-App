<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Requests\StoreClientRequest;
use App\Repositories\ClientContactRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ContactRepository;
use App\Services\ClientCodeGeneratorService;
use Throwable;

final class ClientController
{
    public function index(): string
    {
        $created = isset($_GET['created']) && $_GET['created'] === '1';
        return $this->renderIndex([], [], $created);
    }

    public function store(): string
    {
        [$data, $errors] = (new StoreClientRequest())->validate($_POST);

        if ($errors !== []) {
            http_response_code(422);
            return $this->renderIndex($errors, $data, false);
        }

        $repository = new ClientRepository();
        $generator = new ClientCodeGeneratorService($repository);
        $connection = Database::connection();

        try {
            $connection->beginTransaction();

            // Keep schema compatibility (NOT NULL + UNIQUE client_code) before final generated code.
            $temporaryCode = $this->generateTemporaryClientCode($repository);
            $clientId = $repository->create($data['name'], $temporaryCode);

            $generatedCode = $generator->generateForName($data['name']);
            $repository->updateClientCode($clientId, $generatedCode);

            $connection->commit();
        } catch (Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            http_response_code(500);
            return $this->renderIndex(
                ['general' => 'Unable to create client right now. Please try again.'],
                $data,
                false
            );
        }

        header('Location: /clients?created=1');
        http_response_code(302);
        return '';
    }

    /**
     * @param array<string, string> $errors
     * @param array<string, string> $oldInput
     */
    private function renderIndex(array $errors, array $oldInput, bool $created): string
    {
        $clients = (new ClientRepository())->allSortedByName();
        $contacts = (new ContactRepository())->allSortedBySurnameAndName();
        $links = (new ClientContactRepository())->allLinks();
        $linkStatus = (string) ($_GET['link_status'] ?? '');

        return View::render('clients/index', [
            'title' => 'Clients',
            'clients' => $clients,
            'contacts' => $contacts,
            'links' => $links,
            'errors' => $errors,
            'old' => $oldInput,
            'created' => $created,
            'linkStatus' => $linkStatus,
        ]);
    }

    private function generateTemporaryClientCode(ClientRepository $repository): string
    {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while ($repository->clientCodeExists($code));

        return $code;
    }
}
