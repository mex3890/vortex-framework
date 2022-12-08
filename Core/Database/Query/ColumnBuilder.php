<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlConstraints;
use Core\Abstractions\Enums\SqlExpressions;

class ColumnBuilder
{
    private string $table;
    public string $column_name = '';
    public string $column_type = '';
    public int|null $column_length = null;
    public array|null $options;
    public array $column_constraints = [];
    private const FK_PREFIX = 'FK_';

    /**
     * @param string $table
     * @param string $column_name
     * @param string $column_type
     * @param int|null $column_length
     * @param array|null $options
     */
    public function __construct(
        string $table,
        string $column_name,
        string $column_type,
        ?int $column_length = null,
        ?array $options = null
    )
    {
        $this->table = $table;
        $this->column_name = $column_name;
        $this->column_type = $column_type;
        $this->column_length = $column_length;
        $this->options = $options;
    }

    public function autoIncrement(): static
    {
        $this->column_constraints[] = SqlConstraints::AUTO_INCREMENT->value;
        return $this;
    }

    public function default(int|string $value): static
    {
        $this->column_constraints[] = SqlConstraints::DEFAULT->value . PhpExtra::WHITE_SPACE->value . $value;
        return $this;
    }

    public function foreignKey(string $reference_table, string $column): static
    {
        // " CONSTRAINT FK_users_posts FOREIGN KEY (COLUMN) REFERENCES users (id)
        $this->column_constraints[] =
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::CONSTRAINT->value .
            PhpExtra::WHITE_SPACE->value .
            self::FK_PREFIX .
            $reference_table .
            PhpExtra::UNDERLINE->value .
            $this->table .
            PhpExtra::WHITE_SPACE->value .
            SqlConstraints::FOREIGN_KEY->value .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::OPEN_PARENTHESES->value .
            $this->column_name .
            PhpExtra::END_PARENTHESES->value .
            PhpExtra::WHITE_SPACE->value .
            SqlExpressions::REFERENCES->value .
            PhpExtra::WHITE_SPACE->value .
            $reference_table .
            PhpExtra::WHITE_SPACE->value .
            PhpExtra::OPEN_PARENTHESES->value .
            $column .
            PhpExtra::END_PARENTHESES->value;
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
