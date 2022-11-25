<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class CsrfTokensDoNotMatch extends VortexException
{
    public function __construct()
    {
        parent::__construct('The Session CSRF token do not match with Request CSRF token', 500, Level::Alert->value);
    }
}
