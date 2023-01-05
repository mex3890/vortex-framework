<?php

namespace Core\Database\Relationship;

use Core\Database\Query\SelectBuilder;

class BelongsTo extends Relation
{
    /**
     * @return SelectBuilder
     */
    public function mount(): SelectBuilder
    {
        if (is_null($this->main_column)) {
            $this->main_column = 'id';
        }

        $select_builder = new SelectBuilder($this->secondary_model->table, '*', $this->secondary_model);
        return $select_builder->where($this->main_column, $this->main_id)->limit(1);
    }
}
