<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class Insert
{
    private array|bool $result;

    public function __construct(string $table, array $column_values)
    {
        $insert = $this->mountInsertQuery($table, $column_values);
        $result =  new QueryExecutor(SqlExpressions::INSERT->value, $table, null, $insert);
        $this->result = $result->result;
    }

    private function mountInsertQuery(string $table, array $column_values): string
    {
        $query = SqlExpressions::INSERT->value . " $table ";
        $columns = '(';
        $values = '(';
        $count = count($column_values);
        $i = 1;

        foreach ($column_values as $column => $value) {
            if($i !== $count) {
                $columns .= "$column, ";
                $values .= "'$value', ";
            } else {
                $columns .= "$column)";
                $values .= "'$value');";
            }
            $i++;
        }
        return $query . $columns . ' VALUES ' . $values;
    }

    public function make(): bool|array
    {
        return $this->result;
    }
}
