<?php

namespace Core\Core\Log;

use Monolog\Level;
use Monolog\Logger;

class Log
{
    public static function make(string|array $message, int $mode): void
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }

        $logger = new Logger('vortex_logger');
        $logger->pushHandler(new LogHandler(__DIR__ . '/../../../../../../.log', Level::Debug));

        self::selectMode($logger, $message, $mode);
    }

    private static function selectMode(Logger $logger, string $message, int $mode): void
    {
        match ($mode) {
            Level::Debug->value => $logger->debug($message),
            Level::Alert->value => $logger->alert($message),
            Level::Critical->value => $logger->critical($message),
            Level::Emergency->value => $logger->emergency($message),
            Level::Error->value => $logger->error($message),
            Level::Info->value => $logger->info($message),
            Level::Notice->value => $logger->notice($message),
            Level::Warning->value => $logger->warning($message),
        };
    }
}
