<?php

namespace Core\Database;

class DbTable
{

    public array $columns = [];

    public function bigInt(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'BIGINT');
    }

    public function binary(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'BINARY');
    }

    public function bit(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'BIT');
    }

    public function blob(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'BLOB');
    }

    public function boolean(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'BOOLEAN');
    }

    public function date(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'DATE');
    }

    public function dateTime(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'DATETIME');
    }

    public function double(string $column_name, int $column_length, int $column_decimal_length): DbColumn
    {
        return $this->makeColumn($column_name, 'DOUBLE', $column_length, $column_decimal_length);
    }

    public function enum(string $column_name, array $options): DbColumn
    {
        return $this->makeColumn($column_name, 'ENUM', null, null, $options);
    }

    public function float(string $column_name, int $column_length, int $column_decimal_length): DbColumn
    {
        return $this->makeColumn($column_name, 'FLOAT', $column_length, $column_decimal_length);
    }

    public function id(string $column_name = 'id'): DbColumn
    {
        return $this->makeColumn($column_name, 'BIGINT');
    }

    public function int(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'INT');
    }

    public function longText(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'LONGTEXT');
    }

    public function mediumBlob(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'MEDIUMBLOB');
    }

    public function mediumInt(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'MEDIUMINT');
    }

    public function mediumText(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'MEDIUMTEXT');
    }

    public function text(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'TEXT');
    }

    public function tinyText(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'TINYTEXT');
    }

    public function time(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'TIME');
    }

    public function timeStamp(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'TIMESTAMP');
    }

    public function uuid(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'VARCHAR');
    }

    public function var(string $column_name, int $var_length): DbColumn
    {
        return $this->makeColumn($column_name, 'CHAR', $var_length);
    }

    public function varBinary(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'VARBINARY');
    }

    public function varchar(string $column_name, int $varchar_length): DbColumn
    {
        return $this->makeColumn($column_name, 'VARCHAR', $varchar_length);
    }

    public function smallInt(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'SMALLINT');
    }

    public function year(string $column_name): DbColumn
    {
        return $this->makeColumn($column_name, 'YEAR');
    }

    private function makeColumn(
        string $column_name,
        string $column_type,
        int $column_length = null,
        int $column_decimal_length = null,
        array $options = null
    ): DbColumn
    {
        // TODO: implement decimal_length and options
        $column = new DbColumn($column_name, $column_type, $column_length, $column_decimal_length, $options);
        $this->columns[] = $column;

        return $column;
    }
}
