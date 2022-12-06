<?php

namespace Core\Database;

use Core\Adapters\Collection;

class QueryExecutor
{
    private bool|array|object $result;
    private bool $is_fetch;
    private string $query;

    public function __construct(bool $is_fetch, string $query)
    {
        $this->is_fetch = $is_fetch;
        $this->query = $query;
    }

    private function executeQuery(): void
    {
        $connection = new Connection();

        if ($this->is_fetch) {
            $this->result = $connection->connection->prepare($this->query);
            $this->result->execute();
            $this->result = $this->result->fetchAll();
        } else {
            $this->result = $connection->connection->prepare($this->query)->execute();
        }
    }

    public function execute(): bool|Collection
    {
        $this->executeQuery();

        if (is_array($this->result)) {
            return new Collection($this->result);
        }

        return $this->result;
    }
}
