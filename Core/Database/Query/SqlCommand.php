<?php

namespace Core\Database\Query;


use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Helpers\StringFormatter;

class SqlCommand
{
    private array $filters = [
        'where' => null,
        'order_by' => null,
        'direction' => null,
        'limit' => null
    ];

    public function where(string $column, string $value, string $operator = '='): static
    {
        $value = StringFormatter::scapeQuotes($value);

        if ($operator === 'like') {
            if (!strpos($value, '%')) {
                $value = "%$value%";
            }
        }

        $this->filters['where'] = SqlExpressions::WHERE->value . " $column $operator '$value'";

        return $this;
    }
    //TODO: Implement array functionality
    public function orderBy(array|string $column): static
    {
        $this->filters['order_by'] = SqlExpressions::ORDER_BY->value . " $column";

        return $this;
    }

    public function limit(int $count, int $initial_limit = null): static
    {
        $this->filters['limit'] = SqlExpressions::LIMIT->value . PhpExtra::PHP_WHITE_SPACE->value . ($initial_limit ? "$count, $initial_limit" : $count);

        return $this;
    }

    public function direction(string $direction = 'DESC'): static
    {
        $this->filters['direction'] = $direction;

        return $this;
    }

    protected function mountFilteredQuery(string $query): string
    {
        $query = str_replace(';', ' ', $query);

        foreach ($this->filters as $filter) {
            $filter ? $query .= " $filter" : $query .= '';
        }
        $query .= ';';
        return $query;
    }
}
