<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class FailedOnCreateObjectByModel extends VortexException
{
    public function __construct(string $class)
    {
        parent::__construct("Failed on try create new object instance by $class class", 500, Level::Critical->value);
    }
}
