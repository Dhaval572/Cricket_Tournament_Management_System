<?php

namespace App\Core;

/**
 * One-shot messages shown after a redirect (Post/Redirect/Get pattern).
 */
final class Flash
{
    public static function success(string $message): void
    {
        self::add('success', $message);
    }

    public static function error(string $message): void
    {
        self::add('error', $message);
    }

    public static function add(string $type, string $message): void
    {
        self::ensureSession();

        $_SESSION['flash'][] = [
            'type'    => $type,
            'message' => $message,
        ];
    }

    /**
     * Return all pending messages and clear them (render exactly once).
     *
     * @return array<int, array{type: string, message: string}>
     */
    public static function pull(): array
    {
        self::ensureSession();

        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $messages;
    }

    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
