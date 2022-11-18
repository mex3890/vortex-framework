<?php

namespace Core\Abstractions;

use Core\Core\Log\Log;
use Monolog\Level;

abstract class VortexException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, int $level = null)
    {
        if (!$level) {
            Log::make($message, Level::Debug->value);
        }

        parent::__construct($message, $code);
    }
}
