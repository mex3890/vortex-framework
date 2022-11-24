<?php

namespace Core\Abstractions;

use Core\Database\Query\Select;
use Core\Database\Schema;
use Core\Exceptions\FailedOnCreateObjectByModel;
use Core\Exceptions\FailedOnGetObjectsByModel;
use Core\Exceptions\FailedOnUpdateObjectByModel;
use Core\Exceptions\MissingArguments;
use Core\Request\Paginator;

abstract class Model
{
    protected string $table = '';
    protected array $args;
    protected Select|array $query;
    protected array $result;
    protected string $pagination_links;

    public function __construct(array $args = [])
    {
        $this->args = $args;
    }

    /**
     * @return $this
     * @throws FailedOnCreateObjectByModel
     * @throws MissingArguments
     */
    public function create(): static
    {
        if (isset($this->args)) {
            $model = Schema::insert($this->table, $this->args);

            if (!$model) {
                throw new FailedOnCreateObjectByModel(self::class);
            }

            return self::createObjectByArray($this->args);
        }

        throw new MissingArguments('create');
    }

    /**
     * @param string $column
     * @param string $value
     * @param string $operator
     * @return bool
     * @throws FailedOnCreateObjectByModel
     */
    public static function delete(string $column, string $value, string $operator = '='): bool
    {
        $model = new static();

        $status = Schema::delete($model->table, $column, $value, $operator);

        if (!$status) {
            throw new FailedOnCreateObjectByModel(self::class);
        }

        return true;
    }

    /**
     * @throws FailedOnUpdateObjectByModel
     */
    public function update(array $new_values): static
    {
        $status = Schema::update($this->table, $new_values, $this->args['id']);

        if (!$status) {
            throw new FailedOnUpdateObjectByModel(self::class);
        }

        return self::createObjectByArray($this->args);
    }

    /**
     * @param string $column
     * @return bool|array
     * @throws FailedOnGetObjectsByModel
     */
    public static function first(string $column = 'id'): bool|Model
    {
        $model = new static([]);

        $model = Schema::first($model->table, $column);

        if (!$model) {
            throw new FailedOnGetObjectsByModel(self::class);
        }

        return self::createObjectByArray($model);
    }

    /**
     * @param string $column
     * @return bool|array
     * @throws FailedOnGetObjectsByModel
     */
    public static function last(string $column = 'id'): bool|Model
    {
        $model = new static([]);

        $model = Schema::last($model->table, $column);

        if (!$model) {
            throw new FailedOnGetObjectsByModel(self::class);
        }

        return self::createObjectByArray($model);
    }

    public static function find(array|string $select_columns = '*', string $search_column = null, string|int $value = null, string $operator = '='): static
    {
        $newModel = new static();

        if (is_null($search_column) && is_null($value)) {
            $newModel->query = Schema::select($newModel->table, $select_columns);
        } else {
            $newModel->query = Schema::select($newModel->table, $select_columns)->where($search_column, $value, $operator);
        }

        return $newModel;
    }

    private static function createObjectByArray(array $args): static
    {
        $object = new static($args);

        foreach ($object->args as $key => $arg) {
            $object->$key = $arg;
        }

        unset($object->args);
        unset($object->query);
        unset($object->table);
        unset($object->pagination_links);
        unset($object->result);

        return $object;
    }

    /**
     * @param int $model_per_page
     * @param bool $with_previous_button
     * @param bool $with_next_button
     * @param int $max_number_before_break // The max number of pages before use ellipses to simplify links.
     * @return $this
     */
    public function pagination(
        int $model_per_page,
        bool $with_previous_button = true,
        bool $with_next_button = true,
        int $max_number_before_break = 10
    ): static
    {
        if (!empty($this->result = $this->query->make())) {
            if (isset($this->query) && $this->query instanceof Select) {
                $paginator = new Paginator(
                    count($this->result),
                    $model_per_page,
                    $with_previous_button,
                    $with_next_button,
                    $max_number_before_break
                );

                $this->pagination_links = $paginator->mountLinks();
                $page_limits = $paginator->getOffsetAndLimit();
                $this->query = $this->query->limit($page_limits['min'], $page_limits['max']);
            }
        } else {
            $this->query = [];
        }

        return $this;
    }

    public function get(): array|static
    {
        if (!empty($this->query)) {
            $models = $this->query->make();

            if (!isset($this->pagination_links)) {
                if (count($models) > 1) {
                    $result = [];

                    foreach ($models as $model) {
                        $result[] = self::createObjectByArray($model);
                    }
                } else {
                    $result = self::createObjectByArray($models[0]);
                }
            } else {
                $result = [];

                foreach ($models as $model) {
                    $result[] = self::createObjectByArray($model);
                }

                $_GET['PAGINATION_LINKS'] = $this->pagination_links;
            }

            return $result;
        }

        return [];
    }
}
