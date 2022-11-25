<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class MissingCsrfToken extends VortexException
{
    public function __construct()
    {
        parent::__construct('Missing CSRF token, probably you forgot set CSRF on view', 500, Level::Alert->value);
    }
}
