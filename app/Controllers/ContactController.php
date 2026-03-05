<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\ContactRepository;

final class ContactController
{
    public function index(): string
    {
        $contacts = (new ContactRepository())->allSortedBySurnameAndName();

        return View::render('contacts/index', [
            'title' => 'Contacts',
            'contacts' => $contacts,
        ]);
    }
}
