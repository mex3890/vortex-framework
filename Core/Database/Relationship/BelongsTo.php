<?php

namespace Core\Database\Relationship;

use Core\Database\Query\SelectBuilder;
use Core\Database\Schema;

class BelongsTo extends Relation
{
    public function mount(): SelectBuilder
    {
        if (is_null($this->secondary_column)) {
            $this->secondary_column = 'id';
        }

        $select_builder = new SelectBuilder($this->secondary_model->table, '*', $this->secondary_model);
        return $select_builder->where($this->secondary_column, $this->main_id)->limit(1);
    }
}
