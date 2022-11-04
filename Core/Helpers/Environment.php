<?php

namespace Core\Helpers;

class Environment
{
    private const APP_NAME = 'APP_NAME';
    private const APP_ENV = 'APP_ENV';
    private const APP_URL = 'APP_URL';
    private const DB_CONNECTION = 'DB_CONNECTION';
    private const DB_HOST = 'DB_HOST';
    private const DB_PORT = 'DB_PORT';
    private const DB_DATABASE = 'DB_DATABASE';
    private const DB_USERNAME = 'DB_USERNAME';
    private const DB_PASSWORD = 'DB_PASSWORD';
    private const DB_CHARSET = 'DB_CHARSET';
    private const TIME_ZONE = 'TIME_ZONE';
    private const DATE_FORMAT = 'DATE_FORMAT';
    private const TIME_FORMAT = 'TIME_FORMAT';
    private const MAIL_HOST = 'MAIL_HOST';
    private const MAIL_USERNAME = 'MAIL_USERNAME';
    private const MAIL_PASSWORD = 'MAIL_PASSWORD';
    private const MAIL_SMTP_SECURE = 'MAIL_SMTP_SECURE';
    private const MAIL_PORT = 'MAIL_PORT';

    public static function appName()
    {
        return $_ENV[self::APP_NAME] ?? null;
    }

    public static function appEnv()
    {
        return $_ENV[self::APP_ENV] ?? null;
    }

    public static function appUrl()
    {
        return $_ENV[self::APP_URL] ?? null;
    }

    public static function dbConnection()
    {
        return $_ENV[self::DB_CONNECTION] ?? null;
    }

    public static function dbHost()
    {
        return $_ENV[self::DB_HOST] ?? null;
    }

    public static function dbPort()
    {
        return $_ENV[self::DB_PORT] ?? null;
    }

    public static function dbDatabase()
    {
        return $_ENV[self::DB_DATABASE] ?? null;
    }

    public static function dbUsername()
    {
        return $_ENV[self::DB_USERNAME] ?? null;
    }

    public static function dbPassword()
    {
        return $_ENV[self::DB_PASSWORD] ?? null;
    }

    public static function dbCharset()
    {
        return $_ENV[self::DB_CHARSET] ?? null;
    }

    public static function timeZone()
    {
        return $_ENV[self::TIME_ZONE] ?? null;
    }

    public static function dateFormat()
    {
        return $_ENV[self::DATE_FORMAT] ?? null;
    }

    public static function timeFormat()
    {
        return $_ENV[self::TIME_FORMAT] ?? null;
    }

    public static function mailHost()
    {
        return $_ENV[self::MAIL_HOST] ?? null;
    }

    public static function mailUsername()
    {
        return $_ENV[self::MAIL_USERNAME] ?? null;
    }

    public static function mailPassword()
    {
        return $_ENV[self::MAIL_PASSWORD] ?? null;
    }

    public static function mailSmtpSecure()
    {
        return $_ENV[self::MAIL_SMTP_SECURE] ?? null;
    }

    public static function mailPort()
    {
        return $_ENV[self::MAIL_PORT] ?? null;
    }
}
