<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class MissingRequestMethodDefinition extends VortexException
{
    public function __construct(string $route_method, string $request_method)
    {
        parent::__construct(
            "The route method is \"$route_method\" provided \"$request_method\".",
            500,
            Level::Critical->value
        );
    }
}
