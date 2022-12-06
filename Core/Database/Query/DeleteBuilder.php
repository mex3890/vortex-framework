<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;
use Core\Traits\JoinClauses;
use Core\Traits\QueryConditionals;

class DeleteBuilder extends QueryBuilder
{
    use JoinClauses, QueryConditionals;

    protected function callQueryBuilder()
    {
        $this->mountQuery();

        if (!empty($this->conditionals)) {
            $this->query = $this->mountConditionalQuery($this->query);
        }
    }

    protected function mountQuery(): void
    {
        $this->query = SqlExpressions::DELETE_FROM->value .
            PhpExtra::WHITE_SPACE->value .
            $this->table .
            PhpExtra::SEMICOLON->value;
    }

    public function get(): bool
    {
        parent::get();

        $response = new QueryExecutor(false, $this->query);

        return $response->execute();
    }
}