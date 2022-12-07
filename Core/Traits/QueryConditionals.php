<?php

namespace Core\Traits;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\Query\SelectBuilder;
use Core\Helpers\StringFormatter;

trait QueryConditionals
{
    private array $conditionals = [
        'WHERE' => null,
        'AND' => null,
        'OR' => null
    ];

    private array $default_conditions_values = [
        'WHERE' => null,
        'AND' => null,
        'OR' => null
    ];

    private bool $has_condition = false;

    public function exists(): static
    {
        $this->has_condition = true;
    }

    public function first(): static
    {
        $this->has_condition = true;

    }

    public function having(): static
    {
        $this->has_condition = true;

    }

    public function isNotNull(): static
    {
        $this->has_condition = true;

    }

    public function isNull(): static
    {
        $this->has_condition = true;

    }

    public function last(): static
    {
        $this->has_condition = true;

    }

    /**
     * @param string $column
     * @param string|int $value
     * @param string $operator
     * @return $this
     */
    public function where(string $column, string|int $value, string $operator = '='): static
    {
        $this->has_condition = true;
        $value = StringFormatter::scapeQuotes($value);

        $this->conditionals[SqlExpressions::WHERE->value] = SqlExpressions::WHERE->value .
            PhpExtra::WHITE_SPACE->value .
            $column .
            PhpExtra::WHITE_SPACE->value .
            $operator .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::SINGLE_QUOTE->value .
            $value .
            PhpExtra::SINGLE_QUOTE->value;

        return $this;
    }

    public function and(string $column, string|int $value, string $operator = '='): static
    {
        $this->has_condition = true;
        $value = StringFormatter::scapeQuotes($value);

        $this->conditionals[SqlExpressions::AND->value][] = SqlExpressions::AND->value .
            PhpExtra::WHITE_SPACE->value .
            $column .
            PhpExtra::WHITE_SPACE->value .
            $operator .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::SINGLE_QUOTE->value .
            $value .
            PhpExtra::SINGLE_QUOTE->value;

        return $this;
    }

    public function whereBetween(): static
    {
        $this->has_condition = true;

    }

    public function whereIn(string $column, callable|array $in_clause): static
    {
        $this->has_condition = true;
        $query = SqlExpressions::WHERE->value .
            PhpExtra::WHITE_SPACE->value .
            $column .
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::IN->value .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::OPEN_PARENTHESES->value;

        if (is_array($in_clause)) {
            $values_count = count($in_clause);
            $counter = 1;

            foreach ($in_clause as $clause) {
                if ($counter < $values_count) {
                    $query .= $clause . PhpExtra::COMMA_WHITE_SPACE->value;
                } else {
                    $query .= $clause;
                }

                $counter++;
            }
        } else {
            $query .= call_user_func_array($in_clause, [new SelectBuilder()]);
        }

        $query = substr($query, 0, -1);
        $query .= PhpExtra::END_PARENTHESES->value;

        $this->conditionals[SqlExpressions::WHERE->value] = $query;

        return $this;

    }

    public function whereNot(string $column, string|int $value, string $operator = '='): static
    {
        $this->has_condition = true;
        $value = StringFormatter::scapeQuotes($value);

        $this->conditionals[SqlExpressions::WHERE->value] = SqlExpressions::WHERE->value .
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::NOT->value .
            PhpExtra::WHITE_SPACE->value .
            $column .
            PhpExtra::WHITE_SPACE->value .
            $operator .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::SINGLE_QUOTE->value .
            $value .
            PhpExtra::SINGLE_QUOTE->value;

        return $this;
    }

    /**
     * @param string $column
     * @param string|int $value
     * @param string $operator
     * @return $this
     */
    public function or(string $column, string|int $value, string $operator = '='): static
    {
        $this->has_condition = true;
        $value = StringFormatter::scapeQuotes($value);

        $this->conditionals[SqlExpressions::OR->value][] = SqlExpressions::OR->value .
            PhpExtra::WHITE_SPACE->value .
            $column .
            PhpExtra::WHITE_SPACE->value .
            $operator .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::SINGLE_QUOTE->value .
            $value .
            PhpExtra::SINGLE_QUOTE->value;

        return $this;

    }

    public function whereAll()
    {

    }

    public function whereAny()
    {

    }

    private function mountConditionalQuery(string $query): string
    {
        $query = str_replace(PhpExtra::SEMICOLON->value, PhpExtra::WHITE_SPACE->value, $query);

        if ($this->has_condition) {
            foreach ($this->conditionals as $conditional_query) {
                if ($conditional_query) {
                    if (is_array($conditional_query)) {
                        foreach ($conditional_query as $conditional) {
                            $query .= $conditional . PhpExtra::WHITE_SPACE->value;
                        }
                    } else {
                        $query .= $conditional_query . PhpExtra::WHITE_SPACE->value;
                    }
                }
            }
        }

        $query = substr($query, 0, -1);
        $query .= PhpExtra::SEMICOLON->value;
        $this->conditionals = $this->default_conditions_values;
        return $query;
    }
}