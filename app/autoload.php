<?php
/**
 * Bootstrap: path constant, PSR-4 style autoloader, small view helpers.
 */

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

/**
 * Escape a value for safe HTML output.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Build an internal page URL: url('matches', ['format' => 'T20'])
 */
function url(string $page, array $query = []): string
{
    $params = array_merge(['page' => $page], $query);

    return 'index.php?' . http_build_query($params);
}
