<?php

namespace App\Core;

/**
 * Minimal session based CSRF protection for HTML forms.
 */
final class Csrf
{
    public static function token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Hidden input to place inside every POST form.
     */
    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    /**
     * Reject the request when the posted token is missing or wrong.
     */
    public static function verify(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sent = (string) ($_POST['_csrf'] ?? '');
        $known = self::token();

        if ($sent === '' || !hash_equals($known, $sent)) {
            http_response_code(419);
            exit('Invalid or expired form token. Please go back and try again.');
        }
    }
}
