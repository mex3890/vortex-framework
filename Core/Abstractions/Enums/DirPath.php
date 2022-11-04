<?php

namespace Core\Abstractions\Enums;

enum DirPath: string
{
    case VIEWS = 'Resources\\views';
    case SMARTY_CONFIG = 'Core\\Galaxy\\Config';
    case SMARTY_COMPILE = 'views';
    case SMARTY_CACHE = 'Cache\\views';
}
