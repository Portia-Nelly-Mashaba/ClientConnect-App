<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Requests\StoreContactRequest;
use App\Repositories\ClientContactRepository;
use App\Repositories\ContactRepository;

final class ContactController
{
    public function index(): string
    {
        return $this->renderIndex([], []);
    }

    public function store(): string
    {
        [$data, $errors] = (new StoreContactRequest())->validate($_POST);
        $repository = new ContactRepository();

        if (!isset($errors['email']) && $repository->emailExists($data['email'])) {
            $errors['email'] = 'Email already exists.';
        }

        if ($errors !== []) {
            http_response_code(422);
            return $this->renderIndex($errors, $data);
        }

        $contactId = $repository->create($data['name'], $data['surname'], $data['email']);
        Flash::setStatus('Contact created successfully.');
        header('Location: /contacts/' . $contactId);
        http_response_code(302);
        return '';
    }

    public function show(string $contactId): string
    {
        $id = (int) $contactId;
        $contact = (new ContactRepository())->findById($id);
        if ($contact === null) {
            http_response_code(404);
            return 'Contact not found';
        }

        $tab = (string) ($_GET['tab'] ?? '');
        $openClientsTab = $tab === 'clients';

        return View::render('contacts/show', [
            'title' => 'Contact Details',
            'contact' => $contact,
            'linkedClients' => (new ClientContactRepository())->clientsForContact($id),
            'availableClients' => (new ClientContactRepository())->availableClientsForContact($id),
            'statusMessage' => Flash::pullStatus(),
            'errorMessage' => Flash::pullError(),
            'openClientsTab' => $openClientsTab,
        ]);
    }

    /**
     * @param array<string, string> $errors
     * @param array<string, string> $oldInput
     */
    private function renderIndex(array $errors, array $oldInput): string
    {
        $contacts = (new ContactRepository())->allSortedBySurnameAndName();

        return View::render('contacts/index', [
            'title' => 'Contacts',
            'contacts' => $contacts,
            'errors' => $errors,
            'old' => $oldInput,
            'statusMessage' => Flash::pullStatus(),
            'errorMessage' => Flash::pullError(),
        ]);
    }
}
