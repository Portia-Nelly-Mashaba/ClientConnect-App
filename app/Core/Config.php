<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $configCache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $fileName = array_shift($segments);

        if ($fileName === null || $fileName === '') {
            return $default;
        }

        if (!isset(self::$configCache[$fileName])) {
            $path = BASE_PATH . '/config/' . $fileName . '.php';
            if (!is_file($path)) {
                return $default;
            }

            /** @var array<string, mixed> $config */
            $config = require $path;
            self::$configCache[$fileName] = $config;
        }

        $value = self::$configCache[$fileName];
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
