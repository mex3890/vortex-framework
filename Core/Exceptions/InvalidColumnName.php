<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class InvalidColumnName extends VortexException
{
    public function __construct()
    {
        parent::__construct("The column name can't is ' '", 500, Level::Critical->value);
    }
}
