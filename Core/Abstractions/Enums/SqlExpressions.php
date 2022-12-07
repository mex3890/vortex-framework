<?php

namespace Core\Abstractions\Enums;

enum SqlExpressions: string
{
    case AND = 'AND';
    case ASC = 'ASC';
    case AVG = 'AVG';
    case CONSTRAINT = 'CONSTRAINT';
    case COUNT = 'COUNT';
    case CREATE = 'CREATE';
    case CREATE_TABLE = 'CREATE TABLE';
    case CROSS_JOIN = 'CROSS JOIN';
    case DELETE_FROM = 'DELETE FROM';
    case DESC = 'DESC';
    case DISTINCT = 'DISTINCT';
    case DROP_TABLE_IF_EXIST = /** @lang text */ 'DROP TABLE IF EXISTS';
    case FULL_JOIN = 'FULL JOIN';
    case GROUP_BY = 'GROUP BY';
    case IN = 'IN';
    case INNER_JOIN = 'INNER JOIN';
    case INSERT = 'INSERT INTO';
    case LEFT_JOIN = 'LEFT JOIN';
    case LIMIT = 'LIMIT';
    case NOT = 'NOT';
    case ON = 'ON';
    case OR = 'OR';
    case ORDER_BY = 'ORDER BY';
    case REFERENCES = 'REFERENCES';
    case RIGHT_JOIN = 'RIGHT JOIN';
    case SELECT = 'SELECT';
    case SET = 'SET';
    case SUM = 'SUM';
    case UPDATE = 'UPDATE';
    case VALUES = 'VALUES';
    case WHERE = 'WHERE';
}
