<?php

namespace Core\Abstractions\Enums;

enum SqlExpressions: string
{
    case CREATE = 'CREATE';
    case DELETE_FROM = 'DELETE FROM';
    case UPDATE = 'UPDATE';
    case SELECT = 'SELECT';
    case INSERT = 'INSERT INTO';
    case DROP_TABLE = 'DROP TABLE';
    case LIMIT = 'LIMIT';
    case ORDER_BY = 'ORDER BY';
    case WHERE = 'WHERE';
}
