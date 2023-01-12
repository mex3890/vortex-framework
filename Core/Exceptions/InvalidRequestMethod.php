<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class InvalidRequestMethod extends VortexException
{
    public function __construct(string $method)
    {
        parent::__construct(
            "The method $method is invalid, available methods (GET, POST, PUT, PATCH, DELETE)",
            500,
            Level::Critical->value
        );
    }
}
