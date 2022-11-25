<?php

namespace Core\Request;

use Core\Exceptions\CsrfTokensDoNotMatch;
use Core\Exceptions\MissingCsrfToken;

class Csrf
{
    private const CSRF_TOKEN_KEY = 'csrf_token';

    public function __construct()
    {
        if (!Session::isStarted()) {
            Session::start();
            $this->setSessionTokenIfNotExist();
        }
    }

    /**
     * @return bool
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public static function verifyIfRequestTokenMatchWithSessionToken(): bool
    {
        if (self::verifyIfSessionTokenExist() && self::verifyIfRequestTokenExist()) {
            if (self::getRequestTokenIfExist() === self::getSessionTokenIfExist()) {
                self::regenerateTokenAfterVerification();

                return true;
            }

            self::closeSessionWhenTokensDoNotMatch();

            throw new CsrfTokensDoNotMatch();
        }

        throw new MissingCsrfToken();
    }

    private static function createToken(): string
    {
        return md5(uniqid(mt_rand(), true));
    }

    public static function getRequestTokenIfExist(): string
    {
        return $_POST[self::CSRF_TOKEN_KEY];
    }

    public static function verifyIfRequestTokenExist(): bool
    {
        if (isset($_POST[self::CSRF_TOKEN_KEY])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     * @throws MissingCsrfToken
     */
    public static function getSessionTokenIfExist(): string
    {
        if (self::verifyIfSessionTokenExist()) {
            return $_SESSION[self::CSRF_TOKEN_KEY];
        }

        throw new MissingCsrfToken();
    }

    public static function verifyIfSessionTokenExist(): bool
    {
        if (isset($_SESSION[self::CSRF_TOKEN_KEY]) && $_SESSION[self::CSRF_TOKEN_KEY] !== '') {
            return true;
        }

        return false;
    }

    private function setSessionTokenIfNotExist(): void
    {
        if (!$this->verifyIfSessionTokenExist()) {
            $_SESSION[self::CSRF_TOKEN_KEY] = self::createToken();
        }
    }

    private static function closeSessionWhenTokensDoNotMatch(): void
    {
        Session::destroy();
    }

    private static function regenerateTokenAfterVerification(): void
    {
        $_SESSION[self::CSRF_TOKEN_KEY] = self::createToken();
    }
}
