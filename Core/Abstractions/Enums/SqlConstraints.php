<?php

namespace Core\Abstractions\Enums;

enum SqlConstraints: string
{
    case AUTO_INCREMENT = 'AUTO_INCREMENT';
    case DEFAULT = 'DEFAULT';
    case FOREIGN_KEY = 'FOREIGN KEY';
    case NOT_NULL = 'NOT NULL';
    case PRIMARY_KEY = 'PRIMARY KEY';
    case UNIQUE = 'UNIQUE';
}