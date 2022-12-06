<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use Core\Adapters\Collection;
use Core\Database\QueryExecutor;
use Core\Exceptions\ViolationMinimalPagesBeforeBreakLinksList;
use Core\Request\Paginator;
use Core\Traits\JoinClauses;
use Core\Traits\QueryConditionals;
use Core\Traits\QueryFilters;

class SelectBuilder extends QueryBuilder
{
    use JoinClauses, QueryConditionals, QueryFilters;

    private array|string $columns;
    private array $select_constraints;
    private string $pagination_links;
    private array $pagination_params;

    /**
     * @param string $table
     * @param array|string|null $columns
     * If you do not need to specify the columns and <br>do not want to use the default value ( * ), use null
     */
    public function __construct(string $table = '', null|array|string $columns = '*')
    {
        parent::__construct($table);

        if ($columns !== '' && $columns !== null) {
            $this->columns = $columns;
        }
    }

    protected function callQueryBuilder()
    {
        $this->mountQuery();

        if (!empty($this->join_clauses)) {
            $this->query = $this->mountJoinQuery($this->query);
        }

        if (!empty($this->conditionals)) {
            $this->query = $this->mountConditionalQuery($this->query);
        }

        if (!empty($this->filters)) {
            $this->query = $this->mountFilteredQuery($this->query);
        }

        if (!empty($this->select_constraints)) {
            $this->query = $this->mountSelectConstraints($this->query);
        }
    }

    protected function mountQuery(): void
    {
        $this->query = SqlExpressions::SELECT->value;
        if (is_array($this->columns)) {
            $count = count($this->columns);
            $i = 1;
            foreach ($this->columns as $column) {
                if ($i !== $count) {
                    $this->query .= " $column,";
                } else {
                    $this->query .= " $column";
                }
                $i++;
            }
        } else {
            $this->query .= PhpExtra::WHITE_SPACE->value . $this->columns;
        }

        $this->query .= " from $this->table;";
    }

    public function from(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function column(string $column): static
    {
        $this->columns = $column;

        return $this;
    }

    /**
     * @param string $column
     * @param string|null $alias
     * @return $this
     */
    public function count(string $column, ?string $alias = null): static
    {
        $count_column = SqlExpressions::COUNT->value .
            PhpExtra::OPEN_PARENTHESES->value .
            $column .
            PhpExtra::END_PARENTHESES->value;

        $this->mountCustomColumn($count_column, $alias);

        return $this;
    }

    public function distinct(): static
    {
        $this->select_constraints[] = 'DISTINCT';

        return $this;
    }

    public function avg(string $column, ?string $alias = null): static
    {
        $count_column = SqlExpressions::AVG->value .
            PhpExtra::OPEN_PARENTHESES->value .
            $column .
            PhpExtra::END_PARENTHESES->value;

        $this->mountCustomColumn($count_column, $alias);

        return $this;
    }

    public function sum(string $column, ?string $alias = null): static
    {
        $count_column = SqlExpressions::SUM->value .
            PhpExtra::OPEN_PARENTHESES->value .
            $column .
            PhpExtra::END_PARENTHESES->value;

        $this->mountCustomColumn($count_column, $alias);

        return $this;
    }

    public function union()
    {

    }

    private function mountSelectConstraints(string $query): array|string
    {
        $constraint_string = implode(PhpExtra::WHITE_SPACE->value, $this->select_constraints);

        $pos = strpos($query, SqlExpressions::SELECT->value);

        return substr_replace(
            $query,
            SqlExpressions::SELECT->value . PhpExtra::WHITE_SPACE->value . $constraint_string,
            $pos,
            strlen(SqlExpressions::SELECT->value)
        );
    }

    /**
     * @param int $model_per_page
     * @param bool $with_previous_button
     * @param bool $with_next_button
     * @param int $max_number_before_break
     * @return void
     * @throws ViolationMinimalPagesBeforeBreakLinksList
     */
    public function pagination(
        int  $model_per_page,
        bool $with_previous_button = true,
        bool $with_next_button = true,
        int  $max_number_before_break = 10
    ): void
    {
        if ($max_number_before_break < 7) {
            throw new ViolationMinimalPagesBeforeBreakLinksList($max_number_before_break);
        }

        $this->pagination_params = [
            'model_per_page' => $model_per_page,
            'with_previous_button' => $with_previous_button,
            'with_next_button' => $with_next_button,
            'max_number_before_break' => $max_number_before_break
        ];

        $this->get();
    }

    private function makePagination(Collection $collection)
    {
        if (!empty($collection)) {
            $paginator = new Paginator(
                count($collection),

                $this->pagination_params['model_per_page'],
                $this->pagination_params['with_previous_button'],
                $this->pagination_params['with_next_button'],
                $this->pagination_params['max_number_before_break']
            );

            $this->pagination_links = $paginator->mountLinks();
            $page_limits = $paginator->getOffsetAndLimit();

            $this->query = $this->query . SqlExpressions::LIMIT->value .
                PhpExtra::WHITE_SPACE->value .
                ($page_limits['max'] ? "{$page_limits['min']}, {$page_limits['max']}" : $page_limits['min']);

        } else {
            $this->pagination_links = "
                <nav class='pagination-nav'>
                    <ul class='pagination-links-list'>
                        <li class='page-item'>
                            <a class='page-link' href=''>0</a>
                        </li>
                    </ul>
                </nav>";
        }
    }

    public function get(): Collection|bool|string
    {
        parent::get();

        $response = new QueryExecutor(true, $this->query);

        if (isset($this->pagination_params)) {
            $this->makePagination($response->execute());

            $response = new QueryExecutor(true, $this->query);

            return $response->execute();
        }

        return $response->execute();
    }

    private function mountCustomColumn(string $expression_column, ?string $alias = null)
    {
        if ($alias) {
            $expression_column .= PhpExtra::WHITE_SPACE->value . 'AS' . PhpExtra::WHITE_SPACE->value . $alias;
        }


        if (!isset($this->columns)) {
            $this->columns = $expression_column;
        } else {
            $this->columns .= PhpExtra::COMMA_WHITE_SPACE->value . $expression_column;
        }
    }
}