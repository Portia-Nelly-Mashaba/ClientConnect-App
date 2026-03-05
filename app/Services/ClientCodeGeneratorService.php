<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ClientRepository;
use RuntimeException;

final class ClientCodeGeneratorService
{
    public function __construct(
        private readonly ClientRepository $clients = new ClientRepository()
    ) {
    }

    public function generateForName(string $name): string
    {
        $prefix = $this->buildPrefix($name);

        for ($counter = 1; $counter <= 999; $counter++) {
            $candidateCode = $prefix . str_pad((string) $counter, 3, '0', STR_PAD_LEFT);
            if (!$this->clients->clientCodeExists($candidateCode)) {
                return $candidateCode;
            }
        }

        throw new RuntimeException("No available client code for prefix [{$prefix}].");
    }

    private function buildPrefix(string $name): string
    {
        $lettersOnly = preg_replace('/[^a-z]/i', '', $name) ?? '';
        $basePrefix = strtoupper(substr($lettersOnly, 0, 3));

        if (strlen($basePrefix) >= 3) {
            return $basePrefix;
        }

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $suffixLength = 3 - strlen($basePrefix);

        return $basePrefix . substr($alphabet, 0, $suffixLength);
    }
}
