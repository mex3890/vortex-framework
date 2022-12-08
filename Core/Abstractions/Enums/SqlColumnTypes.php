<?php

namespace Core\Abstractions\Enums;

enum SqlColumnTypes: string
{
    case BIGINT = 'BIGINT';
    case BINARY = 'BINARY';
    case BIT = 'BIT';
    case BLOB = 'BLOB';
    case BOOL = 'BOOL';
    case BOOLEAN = 'BOOLEAN';
    case CHAR = 'CHAR';
    case DATE = 'DATE';
    case DATETIME = 'DATETIME';
    case DECIMAL = 'DECIMAL';
    case DOUBLE = 'DOUBLE';
    case ENUM = 'ENUM';
    case FLOAT = 'FLOAT';
    case INT = 'INT';
    case INTEGER = 'INTEGER';
    case JSON = 'JSON';
    case LONGBLOB = 'LONGBLOB';
    case LONGTEXT = 'LONGTEXT';
    case MEDIUMBLOB = 'MEDIUMBLOB';
    case MEDIUMINT = 'MEDIUMINT';
    case MEDIUMTEXT = 'MEDIUMTEXT';
    case SET = 'SET';
    case SMALLINT = 'SMALLINT';
    case TEXT = 'TEXT';
    case TIME = 'TIME';
    case TIMESTAMP = 'TIMESTAMP';
    case TINYBLOB = 'TINYBLOB';
    case TINYINT = 'TINYINT';
    case TINYTEXT = 'TINYTEXT';
    case VAR = 'VAR';
    case VARBINARY = 'VARBINARY';
    case VARCHAR = 'VARCHAR';
    case YEAR = 'YEAR';
}