<?php

namespace Db;

use PDO;

class Connection
{
    private string $host;
    private string $username;
    private string $password;
    private string $database;

    private PDO $connection;

    public function __construct()
    {
        if (!isset($this->connection)) {
            $this->host = str_replace("'", "", getenv('HOST'));
            $this->username = str_replace("'", "", getenv('USERNAME'));
            $this->password = str_replace("'", "", getenv('PASSWORD'));
            $this->database = str_replace("'", "", getenv('DATABASE'));
            try {
                $connection = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection = $connection;
            } catch (\PDOException $e) {
                die('Could not connect to database. Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}