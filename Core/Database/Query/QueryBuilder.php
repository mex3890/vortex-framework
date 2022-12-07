<?php

namespace Core\Database\Query;

use Core\Adapters\Collection;
use Core\Database\QueryExecutor;

abstract class QueryBuilder
{
    protected string $table;
    protected string $query;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    abstract protected function callQueryBuilder();

    abstract protected function mountQuery(): void;

    public function get()
    {
        $this->callQueryBuilder();
    }
}
