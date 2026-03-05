<?php

declare(strict_types=1);

namespace App\Requests;

final class StoreContactRequest
{
    /**
     * @param array<string, mixed> $input
     * @return array{0: array{name: string, surname: string, email: string}, 1: array<string, string>}
     */
    public function validate(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $surname = trim((string) ($input['surname'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));

        /** @var array<string, string> $errors */
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        } elseif (strlen($name) > 255) {
            $errors['name'] = 'Name may not be greater than 255 characters.';
        }

        if ($surname === '') {
            $errors['surname'] = 'Surname is required.';
        } elseif (strlen($surname) > 255) {
            $errors['surname'] = 'Surname may not be greater than 255 characters.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (strlen($email) > 255) {
            $errors['email'] = 'Email may not be greater than 255 characters.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Email must be a valid email address.';
        }

        return [
            [
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
            ],
            $errors,
        ];
    }
}
