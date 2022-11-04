<?php

namespace Core\Database\Query;

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
        return SqlExpressions::DELETE_FROM->value . " $table WHERE $column $operator '$value';";
    }

    public function make(): bool|array
    {
        return $this->result;
    }
}
