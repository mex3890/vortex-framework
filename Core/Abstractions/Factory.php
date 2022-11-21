<?php

namespace Core\Abstractions;

require_once __DIR__ . '/../Core/global_functions.php';

abstract class Factory
{
    abstract public static function frame(): array;
}