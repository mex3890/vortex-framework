<?php

namespace Core\Abstractions;

use Core\Database\Schema;

abstract class Model
{
    protected string $table = '';

    public string $id;

    protected array $args;

    protected string $query;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function create(): array|bool
    {
        return Schema::insert($this->table, $this->args);
    }

    public function delete(string $column, string $value, string $operator = '='): bool|array
    {
        return Schema::delete($this->table, $column, $value, $operator);
    }

    public function update(array $new_values): bool|array
    {
        return Schema::update($this->table, $new_values, $this->args['id']);
    }

    /**
     * @param string $column If you set $column parameter, this will be used to order according to the same
     * @return array|bool
     */
    public function first(string $column = 'id'): bool|array
    {
        $model = new static([]);
        return Schema::first($model->table, $column);
    }

    /**
     * @param string $column If you set $column parameter, this will be used to order according to the same
     * @return array|bool
     */
    public static function last(string $column = 'id'): bool|array
    {
        $model = new static([]);
        return Schema::last($model->table, $column);
    }

    public static function get(array|string $select_columns = '*', string $search_column = null, string|int $value = null, string $operator = '='): array|Model
    {
        $models = new static([]);
        if (is_null($search_column) && is_null($value)) {
            $models = Schema::select($models->table, $select_columns)->make();
        } else {
            $models = Schema::select($models->table, $select_columns)->where($search_column, $value, $operator)->make();
        }

        $result = [];

        if (count($models) > 1) {
            foreach ($models as $model) {
                $object = new static($model);

                foreach ($object->args as $key => $arg) {
                    $object->$key = $arg;
                }

                unset($object->args);
                unset($object->query);
                unset($object->table);
                $result[] = $object;
            }
        } else {
            $object = new static($models[0]);

            foreach ($object->args as $key => $arg) {
                $object->$key = $arg;
            }

            unset($object->args);
            unset($object->query);
            unset($object->table);
            $result = $object;
        }

        return $result;
    }
}
