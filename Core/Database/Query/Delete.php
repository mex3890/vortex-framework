<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class Delete
{
    private array|bool $result;

    public function __construct(string $table, string $column, string $value, string $operator = '=')
    {
        $delete = $this->mountDeleteQuery($table, $column, $value, $operator);
        $result =  new QueryExecutor(SqlExpressions::DELETE_FROM->value, $table, null, $delete);
        $this->result = $result->result;
    }

    private function mountDeleteQuery(string $table, string $column, string $value, string $operator): string
    {
        return SqlExpressions::DELETE_FROM->value .
            PhpExtra::PHP_WHITE_SPACE->value .
            $table .
            PhpExtra::PHP_WHITE_SPACE->value .
            SqlExpressions::WHERE->value .
            PhpExtra::PHP_WHITE_SPACE->value .
            $column .
            PhpExtra::PHP_WHITE_SPACE->value .
            $operator .
            PhpExtra::PHP_WHITE_SPACE->value .
            "'$value';";
    }

    public function make(): bool|array
    {
        return $this->result;
    }
}
