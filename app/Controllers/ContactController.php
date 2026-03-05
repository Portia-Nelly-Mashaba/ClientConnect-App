<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Requests\StoreContactRequest;
use App\Repositories\ClientContactRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ContactRepository;

final class ContactController
{
    public function index(): string
    {
        $created = isset($_GET['created']) && $_GET['created'] === '1';
        return $this->renderIndex([], [], $created);
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
            return $this->renderIndex($errors, $data, false);
        }

        $repository->create($data['name'], $data['surname'], $data['email']);
        header('Location: /contacts?created=1');
        http_response_code(302);
        return '';
    }

    /**
     * @param array<string, string> $errors
     * @param array<string, string> $oldInput
     */
    private function renderIndex(array $errors, array $oldInput, bool $created): string
    {
        $contacts = (new ContactRepository())->allSortedBySurnameAndName();
        $clients = (new ClientRepository())->allSortedByName();
        $links = (new ClientContactRepository())->allLinks();
        $linkStatus = (string) ($_GET['link_status'] ?? '');

        return View::render('contacts/index', [
            'title' => 'Contacts',
            'contacts' => $contacts,
            'clients' => $clients,
            'links' => $links,
            'errors' => $errors,
            'old' => $oldInput,
            'created' => $created,
            'linkStatus' => $linkStatus,
        ]);
    }
}
