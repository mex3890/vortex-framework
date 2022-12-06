<?php

namespace Core\Database;

use Core\Exceptions\MissingEnvironmentDatabaseConnectionConstants;
use PDO;

class Connection
{
    private const NEEDED_DB_CONSTANTS =
        [
            'DB_CONNECTION',
            'DB_HOST',
            'DB_DATABASE',
            'DB_CHARSET',
            'DB_USERNAME',
            'DB_PASSWORD'
        ];

    private string $DB_CONNECTION;
    private string $DB_HOST;
    private string $DB_DATABASE;
    private string $DB_CHARSET;
    private string $DB_USERNAME;
    private string $DB_PASSWORD;
    public PDO $connection;

    /**
     * @throws MissingEnvironmentDatabaseConnectionConstants
     */
    public function __construct()
    {
        $missed_constants = [];

        foreach (self::NEEDED_DB_CONSTANTS as $constant) {
            if (isset($_ENV[$constant])) {
                $this->{$constant} = $_ENV[$constant];
            } else {
                $missed_constants[] = $constant;
            }
        }

        if (!empty($missed_constants)) {
            throw new MissingEnvironmentDatabaseConnectionConstants($missed_constants);
        }

        $this->makeConnection();
    }

    private function makeConnection(): void
    {
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
        ];

        $this->connection = new PDO(
            $this->mountDns(),
            $this->DB_USERNAME,
            $this->DB_PASSWORD,
            $options
        );
    }

    private function mountDns(): string
    {
        return "$this->DB_CONNECTION:host=$this->DB_HOST;dbname=$this->DB_DATABASE;charset=$this->DB_CHARSET";
    }
}
