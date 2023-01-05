<?php

namespace Core\Database\Relationship;

use Core\Database\Query\SelectBuilder;

class HasOne extends Relation
{
    public function __construct(
        string  $main_model_class,
        string  $secondary_model_class,
        int     $main_id,
        ?string $foreign_key = null,
    )
    {
        parent::__construct($main_model_class, $secondary_model_class, $main_id, null, $foreign_key);
    }

    public function mount(): SelectBuilder
    {
        if (is_null($this->secondary_column)) {
            $this->secondary_column = $this->mountForeignKeyIdColumnName($this->main_model::class);
        }

        $select_builder = new SelectBuilder($this->secondary_model->table, '*', $this->secondary_model);
        return $select_builder->where($this->secondary_column, $this->main_id)->limit(1);
    }
}
