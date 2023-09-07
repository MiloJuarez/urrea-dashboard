<?php

namespace Db;

use PDO;

class Connection
{
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private string $database = 'urrea';

    private PDO $connection;

    public function __construct()
    {
        if (!isset($this->connection)) {
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