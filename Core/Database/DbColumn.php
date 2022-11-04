<?php

namespace Core\Database;

class DbColumn
{
    public string $column_name = '';
    public string $column_type = '';
    public int|null $column_length = null;
    public array $column_constraints = [];

    public function __construct(string $column_name, string $column_type, ?int $column_length = null)
    {
        $this->column_name = $column_name;
        $this->column_type = $column_type;
        $column_length !== null ? $this->column_length = $column_length : $this->column_length = null;
    }

    public function unique()
    {
        $this->column_constraints[] = 'UNIQUE';
        return $this;
    }

    public function notNull()
    {
        $this->column_constraints[] = 'NOT NULL';
        return $this;
    }

    public function autoIncrement()
    {
        $this->column_constraints[] = 'AUTO_INCREMENT';
        return $this;
    }

    public function default(mixed $value)
    {
        $this->column_constraints[] = "DEFAULT $value";
        return $this;
    }

    public function primaryKey()
    {
        //TODO
    }

    public function foreignKey()
    {
        //TODO
    }
}
