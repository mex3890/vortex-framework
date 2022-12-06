<?php

namespace Core\Traits;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Helpers\ArrayFormatter;
use Core\Helpers\StringFormatter;

trait QueryFilters
{
    private array $default_filters = ['GROUP BY', 'ORDER BY', 'LIMIT'];
    private array $filters = [];

    public function groupBy(string|array $columns): static
    {
        $this->mountFilterString(SqlExpressions::GROUP_BY->value, $columns);

        return $this;
    }

    public function limit(int $count, int $initial_limit = null): static
    {
        $this->filters['limit'] = SqlExpressions::LIMIT->value .
            PhpExtra::WHITE_SPACE->value .
            ($initial_limit ? "$count, $initial_limit" : $count);

        return $this;
    }

    /**
     * @param string|array $columns
     * Use like:<br>
     * 'column'<br>
     * ['column', 'column', ...]<br>
     * ['column' => 'DESC', 'column' => null]<br>
     * WARNING: Needed set the key_value, use null for not specify
     * @return $this
     */
    public function orderBy(string|array $columns): static
    {
        $this->mountFilterString(SqlExpressions::ORDER_BY->value, $columns);

        return $this;
    }

    private function mountFilteredQuery(string $query): string
    {
        $query = str_replace(';', '', $query);

        foreach ($this->default_filters as $default_filter) {
            if(isset($this->filters[$default_filter])) {
                $query .= PhpExtra::WHITE_SPACE->value . $this->filters[$default_filter];
            }
        }
        $query .= ';';
        unset($this->filters);

        return $query;
    }

    private function mountFilterString(string $filter_key, string|array $columns): void
    {
        if (is_array($columns)) {
            if (ArrayFormatter::isAssoc($columns)) {
                $sort_columns = StringFormatter::mountStringByArray($columns, true);
            } else {
                $sort_columns = StringFormatter::mountStringByArray($columns);
            }
        } else {
            $sort_columns = $columns;
        }

        $this->filters[$filter_key] = $filter_key . PhpExtra::WHITE_SPACE->value . $sort_columns;
    }
}