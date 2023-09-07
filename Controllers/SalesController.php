<?php

namespace Controllers;

include_once "Repositories/SaleRepository.php";

use Repositories\SaleRepository;

class SalesController
{
    /**
     * @var SaleRepository
     */
    private SaleRepository $saleRepository;

    public function __construct()
    {
        $this->saleRepository = new SaleRepository();
    }

    /**
     * @return bool|string
     */
    public function index(): bool|string
    {
        return $this->saleRepository->index();
    }

    /**
     * @return bool|string
     */
    public function getCustomers(): bool|string
    {
        return $this->saleRepository->getCustomers();
    }

    /**
     * @return bool|string
     */
    public function getDivisions(): bool|string
    {
        return $this->saleRepository->getDivisions();
    }

}