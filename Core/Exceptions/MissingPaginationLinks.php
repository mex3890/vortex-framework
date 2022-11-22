<?php

namespace Core\Exceptions;

use Core\Abstractions\VortexException;
use Monolog\Level;

class MissingPaginationLinks extends VortexException
{
    public function __construct()
    {
        parent::__construct('You trying access the pagination links, but these don\'t exist, verify if you set 
        your model query with pagination() method', 500, Level::Critical->value);
    }
}
