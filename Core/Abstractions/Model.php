<?php

namespace Core\Abstractions;

use Core\Adapters\Collection;
use Core\Database\Query\DeleteBuilder;
use Core\Database\Query\SelectBuilder;
use Core\Database\Query\UpdateBuilder;
use Core\Database\Schema;
use Core\Exceptions\FailedOnCreateObjectByModel;
use Core\Exceptions\MissingArguments;

abstract class Model
{
    protected string $table = '';
    protected array $args;
    protected SelectBuilder|array $query;
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
            $model = Schema::insert($this->table, $this->args)->get();

            if (!$model) {
                throw new FailedOnCreateObjectByModel(self::class);
            }

            return self::createObjectByArray($this->args);
        }

        throw new MissingArguments('create');
    }

    /**
     * @return DeleteBuilder
     */
    public static function delete(): DeleteBuilder
    {
        $model = new static([]);

        return Schema::delete($model->table);
    }

    /**
     * @param array $new_values
     * @return UpdateBuilder
     */
    public function update(array $new_values): UpdateBuilder
    {
        $model = new static([]);

        return Schema::update($model->table, $new_values);
    }

    /**
     * @param string $column
     * @return bool|Collection
     */
    public static function first(string $column = 'id'): bool|Collection
    {
        $model = new static([]);

        return Schema::first($model->table, $column);
    }

    /**
     * @param string $column
     * @return bool|Model
     */
    public static function last(string $column = 'id'): bool|Collection
    {
        $model = new static([]);

        return Schema::last($model->table, $column);
    }

    public static function find(string|array $select_columns = '*'): SelectBuilder
    {
        $model = new static();

        return Schema::select($model->table, $select_columns);
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
}
