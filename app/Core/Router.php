<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Router
{
    /**
     * @var array<string, array<string, callable|array{0: class-string, 1: string}>>
     */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = $this->normalizePath(parse_url($uri, PHP_URL_PATH) ?: '/');
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=UTF-8');
            echo '404 Not Found';
            return;
        }

        $result = $this->resolve($handler);
        if (is_string($result)) {
            echo $result;
        }
    }

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     */
    private function add(string $method, string $path, callable|array $handler): void
    {
        $normalizedPath = $this->normalizePath($path);
        $this->routes[$method][$normalizedPath] = $handler;
    }

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     */
    private function resolve(callable|array $handler): mixed
    {
        if (is_callable($handler)) {
            return $handler();
        }

        [$controllerClass, $method] = $handler;
        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new RuntimeException("Route handler method not found: {$controllerClass}::{$method}");
        }

        return $controller->{$method}();
    }

    private function normalizePath(string $path): string
    {
        if ($path === '') {
            return '/';
        }

        $normalized = '/' . trim($path, '/');
        return $normalized === '//' ? '/' : $normalized;
    }
}
