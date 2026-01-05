<?php

namespace Core;

use http\Exception\RuntimeException;

final class Session {
    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE ) {
            throw new RuntimeException('Session is not started. Call session_start() to activate it.');
        }
    }

    public function get(string $key) : mixed {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value) : void {
        $_SESSION[$key] = $value;
    }

    public function flash (string $key, mixed $value) : void {
        $_SESSION['flash_' . $key] = $value;
    }

    public function pullFlash (string $key) : mixed {
        $value = $_SESSION['flash_' . $key] ?? null;
        unset($_SESSION['flash_' . $key]);
        return $value;
    }
}