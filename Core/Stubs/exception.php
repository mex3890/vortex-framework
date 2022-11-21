<?php

namespace App\Exceptions;

use Core\Abstractions\VortexException;

abstract class MountException extends VortexException
{
    public function __construct(string $message = "", int $code = 0, int $level = null)
    {
        parent::__construct($message, $code, $level);
    }
}
