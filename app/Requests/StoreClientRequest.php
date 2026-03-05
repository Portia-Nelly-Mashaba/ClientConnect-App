<?php

declare(strict_types=1);

namespace App\Requests;

final class StoreClientRequest
{
    /**
     * @param array<string, mixed> $input
     * @return array{0: array{name: string}, 1: array<string, string>}
     */
    public function validate(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));

        /** @var array<string, string> $errors */
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        } elseif (strlen($name) > 255) {
            $errors['name'] = 'Name may not be greater than 255 characters.';
        }

        return [
            ['name' => $name],
            $errors,
        ];
    }
}
