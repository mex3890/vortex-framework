<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class MissingArguments extends VortexException
{
    public function __construct(string $method)
    {
        parent::__construct("Missing needed arguments on $method", 500, Level::Critical->value);
    }
}
