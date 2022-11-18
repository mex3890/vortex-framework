<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class MissingEnvironmentDatabaseConnectionConstants extends VortexException
{
    public function __construct()
    {
        parent::__construct('You need set all .env Database constants, just the password can stay like "DB_PASSWORD= "', 500, Level::Critical->value);
    }
}
