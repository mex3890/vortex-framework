<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class Select extends SqlCommand
{
    private string $table;
    private array|string $columns;

    public function __construct(string $table, array|string $select_columns = '*')
    {
        $this->table = $table;
        $this->columns = $select_columns;
    }

    private function mountSelectQuery(string $table, array|string $select_columns): string
    {
        $query = SqlExpressions::SELECT->value;
        if (is_array($select_columns)) {
            $count = count($select_columns);
            $i = 1;
            foreach ($select_columns as $column) {
                if ($i !== $count) {
                    $query .= " $column,";
                } else {
                    $query .= " $column";
                }
                $i++;
            }
        } else {
            $query .= ' *';
        }

        $query .= " from $table;";

        return $query;
    }

    public function make(): array|bool
    {
        $select = $this->mountSelectQuery($this->table, $this->columns);
        $select = $this->mountFilteredQuery($select);
        $result = new QueryExecutor(SqlExpressions::SELECT->value, $this->table, null, $select);
        return $result->result;
    }
}
