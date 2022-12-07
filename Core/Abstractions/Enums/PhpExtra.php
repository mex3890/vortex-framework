<?php

namespace Core\Abstractions\Enums;

enum PhpExtra: string
{
    case COMMA = ',';
    case COMMA_WHITE_SPACE = ', ';
    case OPEN_PARENTHESES = '(';
    case END_PARENTHESES = ')';
    case SEMICOLON = ';';
    case WHITE_SPACE = ' ';
    case SINGLE_QUOTE = "'";
    case DOUBLE_QUOTE = '"';
    case EQUAL_OPERATOR = '=';
    case UNDERLINE = '_';
    case END_POINT = '.';
    case PERCENTAGE = '%';
}
