<?php

namespace App\Core;

/**
 * Base controller: renders views inside the shared layout and
 * redirects after POST requests (Post/Redirect/Get).
 */
abstract class Controller
{
    /**
     * Render a template (views/<name>.php) inside views/layout.php.
     */
    protected function view(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require BASE_PATH . '/views/' . $template . '.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layout.php';
    }

    protected function redirect(string $page, array $query = []): void
    {
        header('Location: ' . url($page, $query));
        exit;
    }
}
