<?php

namespace Core\Database\Relationship;

use Core\Abstractions\Model;
use Core\Helpers\ClassManager;

abstract class Relation
{
    protected Model $main_model;
    protected Model $secondary_model;
    protected string $main_column;
    protected ?string $secondary_column;
    protected ?string $pivot_table;
    protected int $main_id;

    public function __construct(
        string $main_model_class,
        string $secondary_model_class,
        int $main_id,
        string $main_column = 'id',
        ?string $secondary_column = null,
        ?string $pivot_table = null
    )
    {
        $this->main_model = new $main_model_class;
        $this->secondary_model = new $secondary_model_class;
        $this->main_id = $main_id;
        $this->main_column = $main_column;
        $this->secondary_column = $secondary_column ?? null;
        $this->pivot_table = $pivot_table ?? null;
    }

    abstract public function mount();

    protected function mountForeignKeyIdColumnName(string $model_name): string
    {
        return strtolower(ClassManager::getClassName($model_name, false)) . '_id';
    }
}
