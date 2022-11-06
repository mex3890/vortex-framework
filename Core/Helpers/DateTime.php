<?php

namespace Core\Helpers;

class DateTime
{
    public static function currentDate(): string
    {
        return date($_ENV['DATE_FORMAT']);
    }

    public static function currentTime(): string
    {
        return date($_ENV['TIME_FORMAT']);
    }

    public static function currentDateTime(): string
    {
        return date($_ENV['DATE_FORMAT'] . ' ' . $_ENV['TIME_FORMAT']);
    }

    public static function retrieveCurrentMillisecond(): float
    {
        return floor(microtime(true) * 1000);
    }
}
