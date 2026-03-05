<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    public static function setStatus(string $message): void
    {
        $_SESSION['_flash_status'] = $message;
    }

    public static function setError(string $message): void
    {
        $_SESSION['_flash_error'] = $message;
    }

    public static function pullStatus(): ?string
    {
        $message = $_SESSION['_flash_status'] ?? null;
        unset($_SESSION['_flash_status']);

        return is_string($message) ? $message : null;
    }

    public static function pullError(): ?string
    {
        $message = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_error']);

        return is_string($message) ? $message : null;
    }
}
