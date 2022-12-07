<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlConstraints;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class CreateTableBuilder extends QueryBuilder
{
    private array $tableBuilder;

    public function __construct(string $table, callable $callable)
    {
        parent::__construct($table);

        $this->tableBuilder = call_user_func_array($callable, [new TableBuilder($table)])->columns;
    }

    protected function callQueryBuilder()
    {
        $this->mountQuery();
    }

    protected function mountQuery(): void
    {
        $fk_list = [];
        $count = 1;
        $columns = '';

        $header_create = SqlExpressions::CREATE_TABLE->value .
            PhpExtra::WHITE_SPACE->value .
            $this->table .
            PhpExtra::WHITE_SPACE->value .
            '(';

        $columns_length = count($this->tableBuilder);

        foreach ($this->tableBuilder as $column) {

            $column_constraints = '';

            foreach ($column->column_constraints as $constraint) {
                if (strpos($constraint, SqlConstraints::FOREIGN_KEY->value)) {
                    $fk_list[] = $constraint;
                } else {
                    $column_constraints .= $constraint . ' ';
                }
            }

            if ($count !== $columns_length) {
                if ($column->column_length !== null) {
                    $columns .= $column->column_name . ' ' . $column->column_type . '(' . $column->column_length . ') ' . $column_constraints . ',';
                } else {
                    $columns .= $column->column_name . ' ' . $column->column_type . ' ' . $column_constraints . ',';
                }
            } else {
                if ($column->column_length !== null) {
                    $columns .= $column->column_name . ' ' . $column->column_type . '(' . $column->column_length . ') ' . $column_constraints . ');';
                } else {
                    $columns .= $column->column_name . ' ' . $column->column_type . ' ' . $column_constraints . ');';
                }
            }

            $count++;
        }

        if (!empty($fk_list)) {
            $length = count($fk_list);
            $index = 1;

            $columns = str_replace(');', ',', $columns);

            foreach ($fk_list as $fk) {
                if ($index < $length) {
                    $columns .= "$fk, ";
                } else {
                    $columns .= "$fk);";
                }

                $index++;
            }
        }

        $this->query = $header_create . $columns;
    }

    public function get(): bool
    {
        parent::get();

        $response = new QueryExecutor(false, $this->query);

        return $response->execute();
    }
}
