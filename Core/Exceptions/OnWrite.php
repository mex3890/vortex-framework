<?php

namespace Core\Exceptions;

use Exception;
use Throwable;

class OnWrite extends Exception
{
    public function __construct(string $message = "Exception on try write file", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
