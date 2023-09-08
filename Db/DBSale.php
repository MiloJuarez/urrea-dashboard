<?php

namespace Db;

include_once "Connection.php";
include "Models/Sale.php";

use Models\Sale;

class DBSale
{
    /**
     * @var string
     */
    protected $table = 'sales';

    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct()
    {
        $this->connection = new Connection();
    }

    /**
     * @return bool|array
     */
    public function getData(): bool|array
    {
        $dbConnection = $this->connection->getConnection();
        $strQuery = "SELECT * FROM {$this->table}";

        $query = $dbConnection->prepare($strQuery);
        $query->setFetchMode(\PDO::FETCH_CLASS, 'Models\Sale');
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_BOTH);
    }

    /**
     * @param string $division
     * @return bool|array
     */
    public function getCustomers(string $division): bool|array
    {
        $dbConnection = $this->connection->getConnection();
        $strQuery = "SELECT DISTINCT(customer_brand) as customer FROM {$this->table} WHERE division = :division";

        $query = $dbConnection->prepare($strQuery);
        $query->bindValue(':division', $division);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * @return bool|array
     */
    public function getDivisions(): bool|array
    {
        $dbConnection = $this->connection->getConnection();
        $strQuery = "SELECT DISTINCT(division) as division FROM {$this->table}";

        $query = $dbConnection->prepare($strQuery);
        $query->execute();

        return $query->fetchAll();
    }
}