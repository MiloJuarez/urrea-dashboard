<?php

namespace Models;

class Sale
{
    private int $id;
    private string $division;
    private int $year;
    private string $month;
    private string $customer_brand;
    private float $amount;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    const COLUMNS = [
        'division',
        'year',
        'month',
        'customer_brand',
        'amount',
        'created_at',
        'updated_at',
    ];

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDivision(): string
    {
        return $this->division;
    }

    /**
     * @param string $division
     */
    public function setDivision(string $division): void
    {
        $this->division = $division;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getMonth(): string
    {
        return $this->month;
    }

    /**
     * @param string $month
     */
    public function setMonth(string $month): void
    {
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function getCustomerBrand(): string
    {
        return $this->customer_brand;
    }

    /**
     * @param string $customer_brand
     */
    public function setCustomerBrand(string $customer_brand): void
    {
        $this->customer_brand = $customer_brand;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}