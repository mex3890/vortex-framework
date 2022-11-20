<?php

namespace Core\Database;

use Core\Abstractions\Enums\SqlConstraints;
use Core\Exceptions\InvalidColumnName;

class DbTable
{

    public array $columns = [];

    /**
     * @throws InvalidColumnName
     */
    public function bigInt(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::BIGINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function binary(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::BINARY->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function bit(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::BIT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function blob(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::BLOB->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function boolean(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::BOOLEAN->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function date(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::DATE->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function dateTime(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::DATETIME->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function double(string $column_name, int $column_length, int $column_decimal_length): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::DOUBLE->value, $column_length, $column_decimal_length);
    }

    /**
     * @throws InvalidColumnName
     */
    public function enum(string $column_name, array $options): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::ENUM->value, null, null, $options);
    }

    /**
     * @throws InvalidColumnName
     */
    public function float(string $column_name, int $column_length, int $column_decimal_length): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::FLOAT->value, $column_length, $column_decimal_length);
    }

    /**
     * @throws InvalidColumnName
     */
    public function id(string $column_name = 'id'): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::BIGINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function int(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::INT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function longText(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::LONGTEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function mediumBlob(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::MEDIUMBLOB->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function mediumInt(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::MEDIUMINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function mediumText(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::MEDIUMTEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function text(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::TEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function tinyText(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::TINYTEXT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function time(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::TIME->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function timeStamp(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::TIMESTAMP->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function uuid(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::VARCHAR->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function var(string $column_name, int $var_length): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::CHAR->value, $var_length);
    }

    /**
     * @throws InvalidColumnName
     */
    public function varBinary(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::VARBINARY->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function varchar(string $column_name, int $varchar_length): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::VARCHAR->value, $varchar_length);
    }

    /**
     * @throws InvalidColumnName
     */
    public function smallInt(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::SMALLINT->value);
    }

    /**
     * @throws InvalidColumnName
     */
    public function year(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, SqlConstraints::YEAR->value);
    }

    /**
     * @throws InvalidColumnName
     */
    private function makeColumn(
        string $column_name,
        string $column_type,
        int $column_length = null,
        int $column_decimal_length = null,
        array $options = null
    ): DbColumn
    {
        // TODO: implement decimal_length and options
        if ($column_name === '') {
            throw new InvalidColumnName();
        }

        $column = new DbColumn($column_name, $column_type, $column_length, $column_decimal_length, $options);
        $this->columns[] = $column;

        return $column;
    }
}
