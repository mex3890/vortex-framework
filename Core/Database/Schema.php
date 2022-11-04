<?php

namespace Core\Database;

use Closure;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\Query\Delete;
use Core\Database\Query\Insert;
use Core\Database\Query\Select;
use Core\Database\Query\Update;

class Schema
{

    public static function create(string $table_name, DbTable $table, Closure $callback): void
    {
        $table = $callback->call(new DbTable());
        new QueryExecutor(SqlExpressions::CREATE->value, $table_name, $table);
    }

    public static function dropIfExists(string $table_name): void
    {
        new QueryExecutor(SqlExpressions::DROP_TABLE->value, $table_name);
    }

    public static function select(string $table_name, array|string $select_columns = '*'): Select
    {
        return new Select($table_name, $select_columns);
    }

    public static function insert(string $table_name, array $column_values): bool|array
    {
        $insert = new Insert($table_name, $column_values);
        return $insert->make();
    }

    public static function delete(string $table_name, string $column, string $value, string $operator = '='): array|bool
    {
        $delete = new Delete($table_name, $column, $value, $operator);
        return $delete->make();
    }

    public static function update(string $table_name, array $new_values, string $search_value, string $column = 'id', string $operator = '='): bool|array
    {
        $update = new Update($table_name, $new_values, $search_value, $column, $operator);
        return $update->make();
    }

    public static function last(string $table_name, string $column = 'id'): bool|array
    {
        $last = new Select($table_name);
        return $last->orderBy($column)
            ->direction()
            ->limit(1)
            ->make();
    }

    public static function first(string $table_name, string $column = 'id'): bool|array
    {
        $last = new Select($table_name);
        return $last->orderBy($column)
            ->direction('ASC')
            ->limit(1)
            ->make();
    }
}
