<?php

namespace Core\Helpers;

class Uri
{
    public static function retrieveCurrentPath()
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    public static function retrieveServerParameters()
    {
        return parse_url($_SERVER['REQUEST_URI']);
    }

    public static function getPublicPath(string $path): bool|string
    {
        return $_ENV['APP_URL'] . "/$path";
    }

    public static function getPublicRealPath(string $path): bool|string
    {
        return realpath("$path");
    }

    public static function getRootPath()
    {
        return$_ENV['APP_URL'];
    }

    public static function getViewPath(string $additional_path = ''): string
    {
        return realpath("../Resources/views/$additional_path");
    }
}
