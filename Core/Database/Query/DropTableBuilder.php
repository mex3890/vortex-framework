<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Database\QueryExecutor;

class DropTableBuilder extends QueryBuilder
{
    protected function callQueryBuilder()
    {
        $this->mountQuery();
    }

    protected function mountQuery(): void
    {
        $this->query = SqlExpressions::DROP_TABLE_IF_EXIST->value .
            PhpExtra::WHITE_SPACE->value .
            $this->table .
            PhpExtra::SEMICOLON->value;
    }

    public function get()
    {
        parent::get();

        $response = new QueryExecutor(false, $this->query);

        return $response->execute();
    }
}