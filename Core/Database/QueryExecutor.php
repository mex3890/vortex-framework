<?php

namespace Core\Database;

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
            case 'CREATE':
                $this->table_name = $table_name;
                $this->table = $table;
                $this->mountCreateColumns();
                break;
            case 'INSERT INTO':
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::INSERT->value, $query);
                break;
            case 'SELECT':
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::SELECT->value, $query);
                break;
            case 'DELETE FROM':
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::DELETE_FROM->value, $query);
                break;
            case 'DROP TABLE':
                $this->table_name = $table_name;
                $query = SqlExpressions::DROP_TABLE->value . " $table_name;";
                $this->executeQuery(SqlExpressions::DROP_TABLE->value, $query);
                break;
            case 'UPDATE':
                $this->table_name = $table_name;
                $this->executeQuery(SqlExpressions::UPDATE->value, $query);
                break;
        }
    }

    private function mountCreateColumns(): void
    {
        $count = 1;
        $header_create = "CREATE TABLE $this->table_name (";

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
            case 'SELECT':
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
