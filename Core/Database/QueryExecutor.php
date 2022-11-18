<?php

namespace Core\Database;

use Core\Abstractions\Enums\PhpExtra;
use Core\Abstractions\Enums\SqlExpressions;
use PDO;

class QueryExecutor
{
    private string $table_name = '';
    private array $table = [];
    private string $columns = '';
    public array|bool $result;

    public function __construct(string $primary_command, string $table_name, array $table = null, string $query = null)
    {
        switch ($primary_command) {
            case SqlExpressions::CREATE->value:
                $this->table_name = $table_name;
                $this->table = $table;
                $this->mountCreateColumns();
                break;
            case SqlExpressions::INSERT->value:
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::INSERT->value, $query);
                break;
            case SqlExpressions::SELECT->value:
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::SELECT->value, $query);
                break;
            case SqlExpressions::DELETE_FROM->value:
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::DELETE_FROM->value, $query);
                break;
            case SqlExpressions::DROP_TABLE->value:
                $this->table_name = $table_name;
                $query = SqlExpressions::DROP_TABLE->value . " $table_name;";
                $this->executeQuery(SqlExpressions::DROP_TABLE->value, $query);
                break;
            case SqlExpressions::UPDATE->value:
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::UPDATE->value, $query);
                break;
        }
    }

    private function mountCreateColumns(): void
    {
        $count = 1;

        $header_create = SqlExpressions::CREATE_TABLE->value .
            PhpExtra::PHP_WHITE_SPACE->value .
            $this->table_name .
            PhpExtra::PHP_WHITE_SPACE->value .
            '(';

        $columns_length = count($this->table);

        foreach ($this->table as $column) {

            $column_constraints = '';

            foreach ($column->column_constraints as $constraint) {
                $column_constraints .= $constraint . ' ';
            }

            if ($count !== $columns_length) {
                if ($column->column_length !== null) {
                    $this->columns .= $column->column_name . ' ' . $column->column_type . '(' . $column->column_length . ') ' . $column_constraints . ',';
                } else {
                    $this->columns .= $column->column_name . ' ' . $column->column_type . ' ' . $column_constraints . ',';
                }
            } else {
                if ($column->column_length !== null) {
                    $this->columns .= $column->column_name . ' ' . $column->column_type . '(' . $column->column_length . ') ' . $column_constraints . ');';
                } else {
                    $this->columns .= $column->column_name . ' ' . $column->column_type . ' ' . $column_constraints . ');';
                }
            }
            $count++;
        }
        $this->executeQuery(SqlExpressions::CREATE->value, $header_create . $this->columns);
    }

    private function executeQuery(string $primary_command, string $query): void
    {
        $connection = new Connection();

        switch ($primary_command) {
            case SqlExpressions::SELECT->value:
                $result = $connection->connection->prepare($query);
                $result->execute();
                $this->result = $result->fetchAll(PDO::FETCH_ASSOC);
                break;
            default:
                $this->result = $connection->connection->prepare($query)->execute();
                break;
        }
    }
}
