<?php

namespace Core\Exceptions;

use Exception;
use Throwable;

class UniqueIdOnTable extends Exception
{
    public function __construct(string $message = "You can use only one id for this table", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
