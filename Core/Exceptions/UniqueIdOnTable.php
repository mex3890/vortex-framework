<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class UniqueIdOnTable extends VortexException
{
    public function __construct()
    {
        parent::__construct("You can use only one id for this table", 500, Level::Critical->value);
    }
}
