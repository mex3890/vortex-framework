<?php

namespace Core\Database\Relationship;

use Core\Database\Query\SelectBuilder;
use Core\Helpers\StringFormatter;

class BelongsToMany extends Relation
{
    public function mount(): SelectBuilder
    {
        if (is_null($this->secondary_column)) {
            $this->secondary_column = 'id';
        }

        $singular_main_table = StringFormatter::singularize($this->main_model->table);
        $singular_secondary_table = StringFormatter::singularize($this->secondary_model->table);

        if ($this->pivot_table) {
            $pivot_table_name = $this->pivot_table;
        } else {
            $tables = [$singular_main_table, $singular_secondary_table];
            sort($tables);
            $pivot_table_name = "$tables[0]_$tables[1]";
        }

        $main_foreign_key = $singular_main_table . '_id';
        $secondary_foreign_key = $singular_secondary_table . '_id';

        $select_builder = new SelectBuilder($this->secondary_model->table, '*', $this->secondary_model);
        return $select_builder
            ->innerJoin(
                $pivot_table_name,
                "{$pivot_table_name}.$secondary_foreign_key",
                "{$this->secondary_model->table}.id"
            )
            ->innerJoin(
                $this->main_model->table,
                "$pivot_table_name.$main_foreign_key",
                "{$this->main_model->table}.id"
            )->where('id', $this->main_id, '=', $this->main_model->table);
    }
}
