<?php

namespace Core\Abstractions;

use Core\Adapters\Collection;
use Core\Database\Query\DeleteBuilder;
use Core\Database\Query\SelectBuilder;
use Core\Database\Query\UpdateBuilder;
use Core\Database\Relationship\BelongsTo;
use Core\Database\Relationship\BelongsToMany;
use Core\Database\Relationship\HasMany;
use Core\Database\Relationship\HasOne;
use Core\Database\Schema;
use Core\Exceptions\FailedOnCreateObjectByModel;
use Core\Exceptions\MissingArguments;
use Core\Helpers\ClassManager;
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

        return Schema::update($model->table, $new_values)->where('id', $this->id);
    }

    /**
     * @return bool|Collection
     */
    public static function first(): bool|Model
    {
        $model = new static([]);

        return Schema::first($model->table, 'id', $model);
    }

    /**
     * @return bool|Collection
     */
    public static function last(): bool|Model
    {
        $model = new static([]);

        return Schema::last($model->table, 'id', $model);
    }

    public static function find(string|array $select_columns = '*'): SelectBuilder
    {
        $model = new static();

        return Schema::select($model->table, $select_columns, $model);
    }

    protected function hasOne(string $parent, string $main_column = 'id', string $secondary_column = 'null'): SelectBuilder
    {
        $hasOne = new HasOne(static::class, $parent, $this->id);
        return $hasOne->mount();
    }

    protected function hasMany(string $parent): SelectBuilder
    {
        $hasMany = new HasMany(static::class, $parent, $this->id);
        return $hasMany->mount();
    }

    protected function belongsTo(string $parent): SelectBuilder
    {
        $foreign_key = strtolower(ClassManager::getClassName($parent, false)) . '_id';
        $belongsTo = new BelongsTo(static::class, $parent, $this->$foreign_key);
        return $belongsTo->mount();
    }

    protected function belongsToMany(string $parent, string $pivot_table = null): SelectBuilder
    {
        $belongsToMany = new BelongsToMany(
            static::class,
            $parent,
            $this->id,
            'id',
            null,
            $pivot_table
        );
        return $belongsToMany->mount();
    }
}
