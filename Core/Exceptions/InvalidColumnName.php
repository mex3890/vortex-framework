<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class InvalidColumnName extends VortexException
{
    public function __construct(string $column)
    {
        parent::__construct(
            "The column name can't is '$column', name is invalid because starts with $|#|@",
            500,
            Level::Critical->value
        );
    }
}
