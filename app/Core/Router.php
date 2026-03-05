<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Router
{
    /**
     * @var array<string, list<array{
     *   path: string,
     *   regex: string,
     *   params: list<string>,
     *   handler: callable|array{0: class-string, 1: string}
     * }>>
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
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            /** @var array<int, string> $parameters */
            $parameters = [];
            foreach ($route['params'] as $name) {
                $parameters[] = (string) ($matches[$name] ?? '');
            }

            $result = $this->resolve($route['handler'], $parameters);
            if (is_string($result)) {
                echo $result;
            }
            return;
        }

        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo '404 Not Found';
    }

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     */
    private function add(string $method, string $path, callable|array $handler): void
    {
        $normalizedPath = $this->normalizePath($path);
        [$regex, $params] = $this->compilePattern($normalizedPath);

        $this->routes[$method][] = [
            'path' => $normalizedPath,
            'regex' => $regex,
            'params' => $params,
            'handler' => $handler,
        ];
    }

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     * @param array<int, string> $parameters
     */
    private function resolve(callable|array $handler, array $parameters = []): mixed
    {
        if (is_callable($handler)) {
            return $handler(...$parameters);
        }

        [$controllerClass, $method] = $handler;
        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new RuntimeException("Route handler method not found: {$controllerClass}::{$method}");
        }

        return $controller->{$method}(...$parameters);
    }

    private function normalizePath(string $path): string
    {
        if ($path === '') {
            return '/';
        }

        $normalized = '/' . trim($path, '/');
        return $normalized === '//' ? '/' : $normalized;
    }

    /**
     * @return array{0: string, 1: list<string>}
     */
    private function compilePattern(string $path): array
    {
        if ($path === '/') {
            return ['#^/$#', []];
        }

        $segments = explode('/', trim($path, '/'));
        $regexParts = [];
        $params = [];

        foreach ($segments as $segment) {
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $segment, $matches) === 1) {
                $name = $matches[1];
                $params[] = $name;
                $regexParts[] = '(?<' . $name . '>\d+)';
                continue;
            }

            $regexParts[] = preg_quote($segment, '#');
        }

        return ['#^/' . implode('/', $regexParts) . '$#', $params];
    }
}
