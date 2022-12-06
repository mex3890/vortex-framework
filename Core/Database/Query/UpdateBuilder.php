<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;
use Core\Traits\JoinClauses;
use Core\Traits\QueryConditionals;

class UpdateBuilder extends QueryBuilder
{
    use JoinClauses, QueryConditionals;

    private array $column_values;

    public function __construct(string $table, array $column_values)
    {
        parent::__construct($table);
        $this->column_values = $column_values;
    }

    protected function callQueryBuilder()
    {
        $this->mountQuery();

        if (!empty($this->conditionals)) {
            $this->query = $this->mountConditionalQuery($this->query);
        }
    }

    protected function mountQuery(): void
    {
        $updated_column_values = '';
        $count = count($this->column_values);
        $i = 1;

        foreach ($this->column_values as $column => $value) {
            if ($i !== $count) {
                $updated_column_values .= $column .
                    PhpExtra::WHITE_SPACE->value .
                    PhpExtra::EQUAL_OPERATOR->value .
                    PhpExtra::WHITE_SPACE->value .
                    PhpExtra::SINGLE_QUOTE->value .
                    $value .
                    PhpExtra::SINGLE_QUOTE->value .
                    PhpExtra::COMMA_WHITE_SPACE->value;
            } else {
                $updated_column_values .= $column .
                    PhpExtra::WHITE_SPACE->value .
                    PhpExtra::EQUAL_OPERATOR->value .
                    PhpExtra::WHITE_SPACE->value .
                    PhpExtra::SINGLE_QUOTE->value .
                    $value .
                    PhpExtra::SINGLE_QUOTE->value;
            }

            $i++;
        }

        $this->query = SqlExpressions::UPDATE->value .
            PhpExtra::WHITE_SPACE->value .
            $this->table .
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::SET->value .
            PhpExtra::WHITE_SPACE->value .
            $updated_column_values .
            PhpExtra::SEMICOLON->value;
    }

    public function get(): bool
    {
        parent::get();

        $response = new QueryExecutor(false, $this->query);

        return $response->execute();
    }
}