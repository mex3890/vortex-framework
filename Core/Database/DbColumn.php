<?php

namespace Core\Database;

use Core\Abstractions\Enums\SqlConstraints;
use Core\Abstractions\Enums\SqlExpressions;

class DbColumn
{
    public string $column_name = '';
    public string $column_type = '';
    public int|null $column_length = null;
    public array $column_constraints = [];
    private const PHP_WHITE_SPACE = ' ';

    public function __construct(string $column_name, string $column_type, ?int $column_length = null)
    {
        $this->column_name = $column_name;
        $this->column_type = $column_type;
        $column_length !== null ? $this->column_length = $column_length : $this->column_length = null;
    }

    public function autoIncrement(): static
    {
        $this->column_constraints[] = SqlConstraints::AUTO_INCREMENT->value;
        return $this;
    }

    public function default(int|string $value): static
    {
        $this->column_constraints[] = SqlConstraints::DEFAULT->value . self::PHP_WHITE_SPACE . $value;
        return $this;
    }

    public function foreignKey(string $table, string $column): static
    {
        $this->column_constraints[] =
            SqlConstraints::FOREIGN_KEY->value .
            self::PHP_WHITE_SPACE .
            SqlExpressions::REFERENCES->value .
            $table .
            "($column)";
        return $this;
    }

    public function notNull(): static
    {
        $this->column_constraints[] = SqlConstraints::NOT_NULL->value;
        return $this;
    }

    public function primaryKey(): static
    {
        $this->column_constraints[] = SqlConstraints::PRIMARY_KEY->value;
        return $this;
    }

    public function unique(): static
    {
        $this->column_constraints[] = SqlConstraints::UNIQUE->value;
        return $this;
    }
}
