<?php

namespace Core\Core\Log;

use Core\Helpers\Environment;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

class LogHandler extends StreamHandler
{
    private const LOG_TITLE = '---------------------- VORTEX LOG ----------------------';
    private const LOG_END = '--------------------------------------------------------';

    public function __construct($stream, int|string|Level $level = Level::Debug, bool $bubble = true, ?int $filePermission = null, bool $useLocking = false)
    {
        parent::__construct($stream, $level, $bubble, $filePermission, $useLocking);
        $this->setFormatter($this->mountFormat());
    }

    private function mountFormat(): LineFormatter
    {
        $date_format = Environment::dateFormat() . ' ' . Environment::timeFormat();
        $output = self::LOG_TITLE
            . PHP_EOL
            . PHP_EOL
            . ($_SERVER['REQUEST_METHOD'] ? "Method: " . $_SERVER['REQUEST_METHOD'] . PHP_EOL : '')
            . ($_SERVER['REQUEST_TIME'] ? "Request time: " . $_SERVER['REQUEST_TIME_FLOAT'] . ' ms' . PHP_EOL : '')
            . "Date-time: %datetime%"
            . PHP_EOL
            . "Level: %level_name%"
            . PHP_EOL
            . ($_SERVER['REQUEST_URI'] ? "Context: " . $_SERVER['REQUEST_URI'] . PHP_EOL : '')
            . ($_SERVER['APP_URL'] ? "Server: " . $_SERVER['APP_URL'] . PHP_EOL : '')
            . ($_SERVER['REMOTE_ADDR'] ? "Remote Address: " . $_SERVER['REMOTE_ADDR'] . PHP_EOL : '')
            . ($_SERVER['REMOTE_PORT'] ? "Remote Port: " . $_SERVER['REMOTE_PORT'] . PHP_EOL : '')
            . PHP_EOL
            . "%message%"
            . "%extra%"
            . PHP_EOL
            . PHP_EOL
            . self::LOG_END
            . PHP_EOL;

        return new LineFormatter($output, $date_format);
    }
}
