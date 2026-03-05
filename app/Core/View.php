<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = [], string $layout = 'layout'): string
    {
        $viewPath = BASE_PATH . '/app/Views/' . $view . '.php';
        if (!is_file($viewPath)) {
            throw new RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $viewPath;
        $content = (string) ob_get_clean();

        if ($layout === '') {
            return $content;
        }

        $layoutPath = BASE_PATH . '/app/Views/' . $layout . '.php';
        if (!is_file($layoutPath)) {
            throw new RuntimeException("Layout not found: {$layout}");
        }

        ob_start();
        require $layoutPath;
        return (string) ob_get_clean();
    }
}
