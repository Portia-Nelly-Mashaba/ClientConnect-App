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
    private const PER_PAGE = 5;

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
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $repository = new ContactRepository();
        $total = $repository->countAll();
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * self::PER_PAGE;
        $contacts = $repository->paginatedSortedBySurnameAndName(self::PER_PAGE, $offset);

        return View::render('contacts/index', [
            'title' => 'Contacts',
            'contacts' => $contacts,
            'pagination' => [
                'page' => $page,
                'perPage' => self::PER_PAGE,
                'total' => $total,
                'totalPages' => $totalPages,
            ],
            'errors' => $errors,
            'old' => $oldInput,
            'statusMessage' => Flash::pullStatus(),
            'errorMessage' => Flash::pullError(),
        ]);
    }
}
