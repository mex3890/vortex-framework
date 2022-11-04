<?php

namespace Core\Database;

use PDO;

class Connection
{
    private string $db_connection = '';
    private string $db_host = '';
    private string $db_database = '';
    private string $db_charset = '';
    private string $db_username = '';
    private string $db_password = '';
    public PDO $connection;
    public function __construct()
    {
        $this->db_connection = $_ENV['DB_CONNECTION'];
        $this->db_host = $_ENV['DB_HOST'];
        $this->db_database = $_ENV['DB_DATABASE'];
        $this->db_charset = $_ENV['DB_CHARSET'];
        $this->db_username = $_ENV['DB_USERNAME'];
        $this->db_password = $_ENV['DB_PASSWORD'];

        $this->makeConnection();
    }

    private function makeConnection(): void
    {
        $this->connection = new PDO(
            $this->mountDns(),
            $this->db_username,
            $this->db_password
        );

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function mountDns(): string
    {
        return "$this->db_connection:host=$this->db_host;dbname=$this->db_database;charset=$this->db_charset";
    }
}
