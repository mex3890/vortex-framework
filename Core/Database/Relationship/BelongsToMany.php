<?php

namespace Core\Database\Relationship;

use Core\Database\Query\SelectBuilder;
use Core\Helpers\StringFormatter;

class BelongsToMany extends Relation
{
    /**
     * @return SelectBuilder
     */
    public function mount(): SelectBuilder
    {
        $singular_main_table = StringFormatter::singularize($this->main_model->table);
        $singular_secondary_table = StringFormatter::singularize($this->secondary_model->table);

        if ($this->pivot_table) {
            $pivot_table_name = $this->pivot_table;
        } else {
            $tables = [$singular_main_table, $singular_secondary_table];
            sort($tables);
            $pivot_table_name = "$tables[0]_$tables[1]";
        }
        if (is_null($this->main_column)) {
            $this->main_column = "{$singular_main_table}_id";
        }

        if (is_null($this->secondary_column)) {
            $this->secondary_column = "{$singular_secondary_table}_id";
        }

        $select_builder = new SelectBuilder($this->secondary_model->table, '*', $this->secondary_model);

        return $select_builder
            ->innerJoin(
                $pivot_table_name,
                "{$pivot_table_name}.$this->secondary_column",
                "{$this->secondary_model->table}.id"
            )
            ->innerJoin(
                $this->main_model->table,
                "$pivot_table_name.$this->main_column",
                "{$this->main_model->table}.id"
            )->where('id', $this->main_id, '=', $this->main_model->table);
    }
}
