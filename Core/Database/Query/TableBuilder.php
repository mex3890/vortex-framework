<?php

namespace Core\Database\Query;

use Core\Abstractions\Enums\SqlColumnTypes;
use Core\Exceptions\InvalidColumnName;
use Core\Helpers\StringFormatter;

class TableBuilder
{
    public array $columns = [];
    private string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * @throws InvalidColumnName
     */
    public function bigInt(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BIGINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function binary(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BINARY->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function bit(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BIT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function blob(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BLOB->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function bool(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BOOL->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function boolean(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BOOLEAN->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function char(string $column_name, int $var_length): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::CHAR->value, $var_length);
    }

    /**
     * @throws InvalidColumnName
     */
    public function date(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::DATE->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function dateTime(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::DATETIME->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function decimal(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::DECIMAL->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function double(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::DOUBLE->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function enum(string $column_name, array $options): ColumnBuilder
    {
        return $this->makeColumn(
            $column_name,
            SqlColumnTypes::ENUM->value,
            null,
            $options
        );
    }

    /**
     * @throws InvalidColumnName
     */
    public function float(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::FLOAT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function id(string $column_name = 'id'): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::BIGINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function int(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::INT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function integer(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::INT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function json(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::JSON->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function longBlob(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::LONGBLOB->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function longText(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::LONGTEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function mediumBlob(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::MEDIUMBLOB->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function mediumInt(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::MEDIUMINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function mediumText(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::MEDIUMTEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function set(string $column_name, array $options): ColumnBuilder
    {
        return $this->makeColumn(
            $column_name,
            SqlColumnTypes::SET->value,
            null,
            $options
        );
    }

    /**
     * @throws InvalidColumnName
     */
    public function smallInt(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::SMALLINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function text(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::TEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function time(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::TIME->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function timestamp(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::TIMESTAMP->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function tinyBlob(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::TINYBLOB->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function tinyInt(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::TINYINT->value);
    }
    /**
     * @throws InvalidColumnName
     */
    public function tinyText(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::TINYTEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function uuid(string $column_name = 'id'): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::VARCHAR->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function varBinary(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::VARBINARY->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function varchar(string $column_name, int $varchar_length): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::VARCHAR->value, $varchar_length);
    }

    /**
     * @throws InvalidColumnName
     */
    public function year(string $column_name): ColumnBuilder
    {
        return $this->makeColumn($column_name, SqlColumnTypes::YEAR->value);
    }

    /**
     * @throws InvalidColumnName
     */
    private function makeColumn(
        string $column_name,
        string $column_type,
        int $column_length = null,
        array $options = null
    ): ColumnBuilder
    {
        if ($column_name === '' || StringFormatter::str_starts_with($column_name, ['$', '#', '@'])){
            throw new InvalidColumnName($column_name);
        }

        $column = new ColumnBuilder(
            $this->table,
            $column_name,
            $column_type,
            $column_length,
            $options
        );

        $this->columns[] = $column;

        return $column;
    }
}
