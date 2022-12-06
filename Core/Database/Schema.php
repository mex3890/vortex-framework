<?php

namespace Core\Database;

use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\Query\CreateTableBuilder;
use Core\Database\Query\DeleteBuilder;
use Core\Database\Query\DropTableBuilder;
use Core\Database\Query\InsertBuilder;
use Core\Database\Query\SelectBuilder;
use Core\Database\Query\UpdateBuilder;

class Schema
{
    /**
     * @param string $table
     * @param callable $callback
     * @return bool
     */
    public static function create(string $table, callable $callback): bool
    {
        $table = new CreateTableBuilder($table, $callback);
        return $table->get();
    }

    /**
     * @param string $table
     * @return bool
     */
    public static function drop(string $table): bool
    {
        $response = new DropTableBuilder($table);
        return $response->get();
    }

    /**
     * @param string $table
     * @param array|string $select_columns
     * @return SelectBuilder
     */
    public static function select(string $table, array|string $select_columns = '*'): SelectBuilder
    {
        return new SelectBuilder($table, $select_columns);
    }

    /**
     * @param string $table
     * @param array $column_values
     * @return InsertBuilder
     */
    public static function insert(string $table, array $column_values): InsertBuilder
    {
        return new InsertBuilder($table, $column_values);
    }

    /**
     * @param string $table
     * @return DeleteBuilder
     */
    public static function delete(string $table): DeleteBuilder
    {
        return new DeleteBuilder($table);
    }

    /**
     * @param string $table
     * @param array $new_values
     * @return UpdateBuilder
     */
    public static function update(string $table, array $new_values): UpdateBuilder
    {
        return new UpdateBuilder($table, $new_values);
    }

    /**
     * @param string $table
     * @param string $column
     * @return bool|array
     */
    public static function last(string $table, string $column = 'id'): bool|array
    {
        $query = new SelectBuilder($table);
        return $query->orderBy([$column => SqlExpressions::DESC->value])
            ->limit(1)
            ->get();
    }

    /**
     * @param string $table
     * @param string $column
     * @return bool|array
     */
    public static function first(string $table, string $column = 'id'): bool|array
    {
        $query = new SelectBuilder($table);
        return $query->orderBy([$column => SqlExpressions::ASC->value])
            ->limit(1)
            ->get();
    }
}
