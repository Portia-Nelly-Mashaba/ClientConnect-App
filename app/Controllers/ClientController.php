<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Flash;
use App\Core\View;
use App\Requests\StoreClientRequest;
use App\Repositories\ClientContactRepository;
use App\Repositories\ClientRepository;
use App\Services\ClientCodeGeneratorService;
use Throwable;

final class ClientController
{
    public function index(): string
    {
        return $this->renderIndex([], []);
    }

    public function store(): string
    {
        [$data, $errors] = (new StoreClientRequest())->validate($_POST);

        if ($errors !== []) {
            http_response_code(422);
            return $this->renderIndex($errors, $data);
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
                $data
            );
        }

        Flash::setStatus('Client created successfully.');
        header('Location: /clients/' . $clientId);
        http_response_code(302);
        return '';
    }

    public function show(string $clientId): string
    {
        $id = (int) $clientId;
        $client = (new ClientRepository())->findById($id);
        if ($client === null) {
            http_response_code(404);
            return 'Client not found';
        }

        $tab = (string) ($_GET['tab'] ?? '');
        $openContactsTab = $tab === 'contacts';

        return View::render('clients/show', [
            'title' => 'Client Details',
            'client' => $client,
            'linkedContacts' => (new ClientContactRepository())->contactsForClient($id),
            'availableContacts' => (new ClientContactRepository())->availableContactsForClient($id),
            'statusMessage' => Flash::pullStatus(),
            'errorMessage' => Flash::pullError(),
            'openContactsTab' => $openContactsTab,
        ]);
    }

    /**
     * @param array<string, string> $errors
     * @param array<string, string> $oldInput
     */
    private function renderIndex(array $errors, array $oldInput): string
    {
        $clients = (new ClientRepository())->allSortedByName();

        return View::render('clients/index', [
            'title' => 'Clients',
            'clients' => $clients,
            'errors' => $errors,
            'old' => $oldInput,
            'statusMessage' => Flash::pullStatus(),
            'errorMessage' => Flash::pullError(),
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
