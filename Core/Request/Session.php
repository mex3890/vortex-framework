<?php

namespace Core\Request;

class Session
{
    public static function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public static function start(): void
    {
        session_start();
    }

    public static function destroy(): void
    {
        session_destroy();
    }
}
