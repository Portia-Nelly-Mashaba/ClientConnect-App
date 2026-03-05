<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ClientRepository;
use App\Repositories\ClientContactRepository;
use App\Repositories\ContactRepository;
use Throwable;

final class ClientContactController
{
    public function link(): string
    {
        $clientId = (int) ($_POST['client_id'] ?? 0);
        $contactId = (int) ($_POST['contact_id'] ?? 0);
        $returnTo = $this->safeReturnTo((string) ($_POST['return_to'] ?? '/clients'));
        $repository = new ClientContactRepository();
        $clients = new ClientRepository();
        $contacts = new ContactRepository();

        if ($clientId <= 0 || $contactId <= 0) {
            return $this->redirectWithStatus($returnTo, 'invalid');
        }
        if (!$clients->exists($clientId) || !$contacts->exists($contactId)) {
            return $this->redirectWithStatus($returnTo, 'invalid');
        }

        try {
            if (!$repository->link($clientId, $contactId)) {
                return $this->redirectWithStatus($returnTo, 'exists');
            }
        } catch (Throwable $exception) {
            return $this->redirectWithStatus($returnTo, 'error');
        }

        return $this->redirectWithStatus($returnTo, 'linked');
    }

    public function unlink(): string
    {
        $clientId = (int) ($_POST['client_id'] ?? 0);
        $contactId = (int) ($_POST['contact_id'] ?? 0);
        $returnTo = $this->safeReturnTo((string) ($_POST['return_to'] ?? '/clients'));
        $repository = new ClientContactRepository();
        $clients = new ClientRepository();
        $contacts = new ContactRepository();

        if ($clientId <= 0 || $contactId <= 0) {
            return $this->redirectWithStatus($returnTo, 'invalid');
        }
        if (!$clients->exists($clientId) || !$contacts->exists($contactId)) {
            return $this->redirectWithStatus($returnTo, 'invalid');
        }

        try {
            if (!$repository->unlink($clientId, $contactId)) {
                return $this->redirectWithStatus($returnTo, 'missing');
            }
        } catch (Throwable $exception) {
            return $this->redirectWithStatus($returnTo, 'error');
        }

        return $this->redirectWithStatus($returnTo, 'unlinked');
    }

    private function redirectWithStatus(string $returnTo, string $status): string
    {
        $separator = str_contains($returnTo, '?') ? '&' : '?';
        header('Location: ' . $returnTo . $separator . 'link_status=' . rawurlencode($status));
        http_response_code(302);
        return '';
    }

    private function safeReturnTo(string $returnTo): string
    {
        if ($returnTo !== '/clients' && $returnTo !== '/contacts') {
            return '/clients';
        }

        return $returnTo;
    }
}
