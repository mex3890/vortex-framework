<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class Update
{
    private array|bool $result;

    public function __construct(string $table_name, array $new_values, string $search_value, string $column = 'id', string $operator = '=')
    {
        $update = $this->mountUpdateQuery($table_name, $new_values, $search_value, $column, $operator);
        $result =  new QueryExecutor(SqlExpressions::UPDATE->value, $table_name, null, $update);
        $this->result = $result->result;
    }

    private function mountUpdateQuery(string $table, array $new_values, string $search_value, string $search_column = 'id', string $operator = '='): string
    {

        $query = SqlExpressions::UPDATE->value . " $table SET ";
        $count = count($new_values);
        $i = 1;
        $formatted_new_columns = '';
        foreach ($new_values as $column => $new_value) {
            if($i !== $count) {
                $formatted_new_columns .= "$column = '$new_value',";
            } else {
                $formatted_new_columns .= "$column = '$new_value'";
            }

            $i++;
        }
        $where = " WHERE $search_column $operator $search_value;";

        return $query . $formatted_new_columns . $where;
    }

    public function make(): bool|array
    {
        return $this->result;
    }
}
