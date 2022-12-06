<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class InsertBuilder extends QueryBuilder
{
    private array $column_values;

    public function __construct(string $table, array $column_values)
    {
        parent::__construct($table);
        $this->column_values = $column_values;
    }

    protected function callQueryBuilder()
    {
        $this->mountQuery();
    }

    protected function mountQuery(): void
    {
        $values = '';

        if (isset($this->column_values[0])) {
            $columns = $this->mountInsertColumns($this->column_values[0]);
            $count = count($this->column_values);
            $i = 1;

            foreach ($this->column_values as $insert) {
                if ($i !== $count) {
                    $values .= $this->mountInsertValues($insert) . PhpExtra::COMMA_WHITE_SPACE->value;
                } else {
                    $values .= $this->mountInsertValues($insert);
                }

                $i++;
            }
        } else {
            $columns = $this->mountInsertColumns($this->column_values);
            $values .= $this->mountInsertValues($this->column_values);
        }

        $this->query = SqlExpressions::INSERT->value .
            PhpExtra::WHITE_SPACE->value .
            $this->table .
            PhpExtra::WHITE_SPACE->value .
            $columns .
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::VALUES->value .
            PhpExtra::WHITE_SPACE->value .
            $values .
            PhpExtra::SEMICOLON->value;
    }

    private function mountInsertValues(array $column_values): string
    {
        $values = PhpExtra::OPEN_PARENTHESES->value;
        $count = count($column_values);
        $i = 1;

        foreach ($column_values as $value) {
            if ($i !== $count) {
                $values .= PhpExtra::DOUBLE_QUOTE->value .
                    $value .
                    PhpExtra::DOUBLE_QUOTE->value .
                    PhpExtra::COMMA->value;
            } else {
                $values .= PhpExtra::DOUBLE_QUOTE->value .
                    $value .
                    PhpExtra::DOUBLE_QUOTE->value .
                    PhpExtra::END_PARENTHESES->value;
            }

            $i++;
        }

        return $values;
    }

    private function mountInsertColumns(array $column_values): string
    {
        $columns = PhpExtra::OPEN_PARENTHESES->value;
        $count = count($column_values);
        $i = 1;

        foreach ($column_values as $column => $value) {
            if ($i !== $count) {
                $columns .= $column . PhpExtra::COMMA_WHITE_SPACE->value;
            } else {
                $columns .= $column . PhpExtra::END_PARENTHESES->value;
            }

            $i++;
        }

        return $columns;
    }

    public function get(): bool
    {
        parent::get();

        $response = new QueryExecutor(false, $this->query);

        return $response->execute();
    }
}