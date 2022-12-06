<?php

namespace Core\Traits;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;

trait JoinClauses
{
    private array $join_clauses = [];
    private bool $has_join = false;

    /**
     * @param string $table
     * @return static
     */
    public function crossJoin(string $table): static
    {
        $this->has_join = true;
        $this->join_clauses[] = SqlExpressions::CROSS_JOIN->value . PhpExtra::WHITE_SPACE->value . $table;

        return $this;
    }

    /**
     * @param string $table
     * @param string $main_conditional_column
     * @param string $second_conditional_column
     * @return $this
     */
    public function fullJoin(string $table, string $main_conditional_column, string $second_conditional_column): static
    {
        $this->has_join = true;
        $this->join_clauses[] = $this->join(
            SqlExpressions::FULL_JOIN->value,
            $table,
            $main_conditional_column,
            $second_conditional_column
        );

        return $this;
    }

    /**
     * @param string $table
     * @param string $main_conditional_column
     * @param string $second_conditional_column
     * @return $this
     */
    public function innerJoin(string $table, string $main_conditional_column, string $second_conditional_column): static
    {
        $this->has_join = true;
        $this->join_clauses[] = $this->join(
            SqlExpressions::INNER_JOIN->value,
            $table,
            $main_conditional_column,
            $second_conditional_column
        );

        return $this;
    }

    /**
     * @param string $table
     * @param string $main_conditional_column
     * @param string $second_conditional_column
     * @return $this
     */
    public function leftJoin(string $table, string $main_conditional_column, string $second_conditional_column): static
    {
        $this->has_join = true;
        $this->join_clauses[] = $this->join(
            SqlExpressions::LEFT_JOIN->value,
            $table,
            $main_conditional_column,
            $second_conditional_column
        );

        return $this;
    }

    /**
     * @param string $table
     * @param string $main_conditional_column
     * @param string $second_conditional_column
     * @return $this
     */
    public function rightJoin(string $table, string $main_conditional_column, string $second_conditional_column): static
    {
        $this->has_join = true;
        $this->join_clauses[] = $this->join(
            SqlExpressions::RIGHT_JOIN->value,
            $table,
            $main_conditional_column,
            $second_conditional_column
        );

        return $this;
    }

    /**
     * @param string $join_type
     * @param string $table
     * @param string $main_conditional_column
     * @param string $second_conditional_column
     * @return string
     */
    private function join(string $join_type, string $table, string $main_conditional_column, string $second_conditional_column): string
    {
        return $join_type .
            PhpExtra::WHITE_SPACE->value .
            $table .
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::ON->value .
            PhpExtra::WHITE_SPACE->value .
            $this->table .
            PhpExtra::END_POINT->value .
            $main_conditional_column .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::EQUAL_OPERATOR->value .
            PhpExtra::WHITE_SPACE->value .
            $table .
            PhpExtra::END_POINT->value .
            $second_conditional_column;
    }

    private function mountJoinQuery(string $query): string
    {
        $query = str_replace(PhpExtra::SEMICOLON->value, PhpExtra::WHITE_SPACE->value, $query);

        if ($this->has_join) {
            foreach ($this->join_clauses as $join_clause) {
                if ($join_clause) {
                    $query .= $join_clause . PhpExtra::WHITE_SPACE->value;
                }
            }
        }

        $query = substr($query, 0, -1);
        $query .= PhpExtra::SEMICOLON->value;
        unset($this->join_clauses);
        return $query;
    }
}