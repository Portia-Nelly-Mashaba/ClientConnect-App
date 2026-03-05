<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Repositories\ClientRepository;
use App\Repositories\ClientContactRepository;
use App\Repositories\ContactRepository;
use Throwable;

final class ClientContactController
{
    public function linkContactToClient(string $clientId): string
    {
        $clientIdInt = (int) $clientId;
        $contactId = (int) ($_POST['contact_id'] ?? 0);
        $repository = new ClientContactRepository();
        $clients = new ClientRepository();
        $contacts = new ContactRepository();

        if ($clientIdInt <= 0 || $contactId <= 0) {
            return $this->redirectToClient($clientIdInt, 'invalid');
        }
        if (!$clients->exists($clientIdInt) || !$contacts->exists($contactId)) {
            return $this->redirectToClient($clientIdInt, 'invalid');
        }

        try {
            if (!$repository->link($clientIdInt, $contactId)) {
                return $this->redirectToClient($clientIdInt, 'exists');
            }
        } catch (Throwable $exception) {
            return $this->redirectToClient($clientIdInt, 'error');
        }

        return $this->redirectToClient($clientIdInt, 'linked');
    }

    public function unlinkContactFromClient(string $clientId, string $contactId): string
    {
        $clientIdInt = (int) $clientId;
        $contactIdInt = (int) $contactId;
        $repository = new ClientContactRepository();
        $clients = new ClientRepository();
        $contacts = new ContactRepository();

        if ($clientIdInt <= 0 || $contactIdInt <= 0) {
            return $this->redirectToClient($clientIdInt, 'invalid');
        }
        if (!$clients->exists($clientIdInt) || !$contacts->exists($contactIdInt)) {
            return $this->redirectToClient($clientIdInt, 'invalid');
        }

        try {
            if (!$repository->unlink($clientIdInt, $contactIdInt)) {
                return $this->redirectToClient($clientIdInt, 'missing');
            }
        } catch (Throwable $exception) {
            return $this->redirectToClient($clientIdInt, 'error');
        }

        return $this->redirectToClient($clientIdInt, 'unlinked');
    }

    public function linkClientToContact(string $contactId): string
    {
        $contactIdInt = (int) $contactId;
        $clientId = (int) ($_POST['client_id'] ?? 0);
        $repository = new ClientContactRepository();
        $clients = new ClientRepository();
        $contacts = new ContactRepository();

        if ($contactIdInt <= 0 || $clientId <= 0) {
            return $this->redirectToContact($contactIdInt, 'invalid');
        }
        if (!$contacts->exists($contactIdInt) || !$clients->exists($clientId)) {
            return $this->redirectToContact($contactIdInt, 'invalid');
        }

        try {
            if (!$repository->link($clientId, $contactIdInt)) {
                return $this->redirectToContact($contactIdInt, 'exists');
            }
        } catch (Throwable $exception) {
            return $this->redirectToContact($contactIdInt, 'error');
        }

        return $this->redirectToContact($contactIdInt, 'linked');
    }

    public function unlinkClientFromContact(string $contactId, string $clientId): string
    {
        $contactIdInt = (int) $contactId;
        $clientIdInt = (int) $clientId;
        $repository = new ClientContactRepository();
        $clients = new ClientRepository();
        $contacts = new ContactRepository();

        if ($contactIdInt <= 0 || $clientIdInt <= 0) {
            return $this->redirectToContact($contactIdInt, 'invalid');
        }
        if (!$contacts->exists($contactIdInt) || !$clients->exists($clientIdInt)) {
            return $this->redirectToContact($contactIdInt, 'invalid');
        }

        try {
            if (!$repository->unlink($clientIdInt, $contactIdInt)) {
                return $this->redirectToContact($contactIdInt, 'missing');
            }
        } catch (Throwable $exception) {
            return $this->redirectToContact($contactIdInt, 'error');
        }

        return $this->redirectToContact($contactIdInt, 'unlinked');
    }

    private function redirectToClient(int $clientId, string $status): string
    {
        $messages = [
            'linked' => ['type' => 'status', 'text' => 'Contact linked to client successfully.'],
            'unlinked' => ['type' => 'status', 'text' => 'Contact unlinked from client successfully.'],
            'exists' => ['type' => 'error', 'text' => 'Contact is already linked to this client.'],
            'missing' => ['type' => 'error', 'text' => 'Contact is not linked to this client.'],
            'invalid' => ['type' => 'error', 'text' => 'Please select a valid contact.'],
            'error' => ['type' => 'error', 'text' => 'Unable to update links right now.'],
        ];
        $message = $messages[$status] ?? $messages['error'];
        if ($message['type'] === 'status') {
            Flash::setStatus($message['text']);
        } else {
            Flash::setError($message['text']);
        }

        if ($clientId <= 0) {
            header('Location: /clients');
            http_response_code(302);
            return '';
        }

        header('Location: /clients/' . $clientId . '?tab=contacts');
        http_response_code(302);
        return '';
    }

    private function redirectToContact(int $contactId, string $status): string
    {
        $messages = [
            'linked' => ['type' => 'status', 'text' => 'Client linked to contact successfully.'],
            'unlinked' => ['type' => 'status', 'text' => 'Client unlinked from contact successfully.'],
            'exists' => ['type' => 'error', 'text' => 'Client is already linked to this contact.'],
            'missing' => ['type' => 'error', 'text' => 'Client is not linked to this contact.'],
            'invalid' => ['type' => 'error', 'text' => 'Please select a valid client.'],
            'error' => ['type' => 'error', 'text' => 'Unable to update links right now.'],
        ];
        $message = $messages[$status] ?? $messages['error'];
        if ($message['type'] === 'status') {
            Flash::setStatus($message['text']);
        } else {
            Flash::setError($message['text']);
        }

        if ($contactId <= 0) {
            header('Location: /contacts');
            http_response_code(302);
            return '';
        }

        header('Location: /contacts/' . $contactId . '?tab=clients');
        http_response_code(302);
        return '';
    }
}
