<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class ViolationMinimalPagesBeforeBreakLinksList extends VortexException
{
    public function __construct(string $number)
    {
        parent::__construct("The minimal number of pages before break links list is 7, passed $number", 500, Level::Critical->value);
    }
}
