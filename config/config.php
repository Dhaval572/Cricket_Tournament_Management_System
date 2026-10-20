<?php
/**
 * Application configuration.
 *
 * Every value can be overridden with an environment variable of the
 * same name (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, APP_DEBUG).
 */

return [
    'db' => [
        'host'    => getenv('DB_HOST') ?: 'localhost',
        'user'    => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'name'    => getenv('DB_NAME') ?: 'cricket_tournament',
        'charset' => 'utf8mb4',
    ],

    'app' => [
        'name'  => 'Cricket Tournament Management System',
        'debug' => filter_var(getenv('APP_DEBUG') ?: '1', FILTER_VALIDATE_BOOLEAN),
    ],
];
