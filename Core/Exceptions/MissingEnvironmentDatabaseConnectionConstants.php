<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Core\Helpers\StringFormatter;
use Monolog\Level;

class MissingEnvironmentDatabaseConnectionConstants extends VortexException
{
    public function __construct(array $missed_constants = null)
    {
        if (!is_null($missed_constants)) {
            $missed_constants = StringFormatter::mountStringByArray($missed_constants);

            parent::__construct(
                "Forgot the following constants in your .env: $missed_constants",
                500, Level::Critical->value
            );
        }
        parent::__construct(
            'You need set all .env Database constants, just the password can stay like "DB_PASSWORD= "',
            500,
            Level::Critical->value
        );
    }
}
