<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class OnWrite extends VortexException
{
    public function __construct()
    {
        parent::__construct("Exception on try write file", 500, Level::Critical->value);
    }
}
