<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class RouteNotFound extends VortexException
{
    public function __construct(string $route_name)
    {
        parent::__construct(
            "The route named \"$route_name\" does not exist",
            500,
            Level::Critical->value
        );
    }
}
