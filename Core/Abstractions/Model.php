<?php

namespace Core\Abstractions;

use Core\Adapters\Collection;
use Core\Database\Query\DeleteBuilder;
use Core\Database\Query\SelectBuilder;
use Core\Database\Query\UpdateBuilder;
use Core\Database\Schema;
use Core\Exceptions\FailedOnCreateObjectByModel;
use Core\Exceptions\MissingArguments;
use Core\Helpers\ObjectConstructor;

abstract class Model
{
    public string $table = '';
    public array $args;

    public function __construct(array $args = [])
    {
        $this->args = $args;
    }

    /**
     * @return object
     * @throws FailedOnCreateObjectByModel
     * @throws MissingArguments
     */
    public function create(): object
    {
        if (isset($this->args)) {
            $model = Schema::insert($this->table, $this->args)->get();

            if (!$model) {
                throw new FailedOnCreateObjectByModel(self::class);
            }

            return ObjectConstructor::mountModelObject(new static(), $this->args);
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
     * @return bool|Collection
     */
    public static function first(): bool|Collection
    {
        $model = new static([]);

        return Schema::first($model->table);
    }

    /**
     * @return bool|Collection
     */
    public static function last(): bool|Collection
    {
        $model = new static([]);

        return Schema::last($model->table);
    }

    public static function find(string|array $select_columns = '*'): SelectBuilder
    {
        $model = new static();

        return Schema::select($model->table, $select_columns, new static);
    }
}
