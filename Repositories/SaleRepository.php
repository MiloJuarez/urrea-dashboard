<?php

namespace Repositories;

include_once "Db/DBSale.php";

use Db\DBSale;

class SaleRepository
{
    /**
     * @var DBSale
     */
    private DBSale $dbSale;

    public function __construct()
    {
        $this->dbSale = new DBSale();
    }

    /**
     * @return bool|string
     */
    public function index(): bool|string
    {
        try {
            $sales = $this->dbSale->getData();
            $sales = $this->getSanitizedData($sales);

            $queryString = explode('?', $_SERVER['REQUEST_URI']);

            $params = [];
            if (count($queryString) > 1 && end($queryString)) {
                $keyValues = explode('&', end($queryString));
                foreach ($keyValues as $keyValue) {
                    $pairValues = explode('=', $keyValue);
                    $params[$pairValues[0]] = end($pairValues);
                }
            }

            $filteredSales = $this->filter($sales, $params);

            return json_encode([
                'success' => true,
                'sales' => $filteredSales,
                'year_range' => $this->getYearRanges($sales)
            ]);
        }catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * @return bool|string
     */
    public function getCustomers(): bool|string
    {
        try {
            $customers = $this->dbSale->getCustomers();
            $customers = array_map(fn($customer) => [
                'customer' => $this->removeAccents($customer['customer'])
            ], $customers);

            return json_encode([
                'success' => true,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * @return bool|string
     */
    public function getDivisions(): bool|string
    {
        try {
            $divisions = $this->dbSale->getDivisions();
            $divisions = array_map(fn($division) => [
                'division' => $this->removeAccents($division['division'])
            ], $divisions);

            return json_encode([
                'success' => true,
                'divisions' => $divisions
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * @param array $sales
     * @param array $filters
     * @return array
     */
    private function filter(array $sales, array $filters): array
    {
        if (!empty($filters['type']) && !empty($filters['search'])) {
            if ($filters['type'] === 'customer') {
                $sales = $this->filteredByCustomer($sales, $filters['search']);
            }

            if ($filters['type'] === 'division') {
                return $this->filteredByDivision($sales, $filters['search']);
            }
        }

        return $this->groupByMonth($sales);
    }

    /**
     * @param array $sales
     * @param $customerBrand
     * @return array
     */
    private function filteredByCustomer(array $sales, $customerBrand): array
    {
        return array_filter($sales, function ($sales) use ($customerBrand) {
            return $customerBrand === str_replace(' ', '_', $sales['customer_brand']);
        });
    }

    /**
     * @param array $sales
     * @param string $groupBy
     * @return array
     */
    private function filteredByDivision(array $sales, string $groupBy): array
    {
        return match ($groupBy) {
            'month' => $this->groupDivisionByMonth($sales),
            default => $this->groupDivisionByYear($sales),
        };
    }

    /**
     * @param array $sales
     * @return array
     */
    private function groupByMonth(array $sales): array
    {
        $groupedData = [];

        $year = '';

        foreach ($sales as $sale) {
            if ($year !== $sale['year']) {
                $year = $sale['year'];

                $customerSales = array_filter($sales, function ($customerSale) use ($year) {
                    return $year === $customerSale['year'];
                });

                $groupedData[$sale['year']] = [
                    'year' => $sale['year'],
                    'customer' => $sale['customer_brand'],
                    'data' => [...$customerSales]
                ];
            }

        }

        return [...$groupedData];
    }

    /**
     * @param array $sales
     * @return array
     */
    private function groupDivisionByYear(array $sales): array
    {
        $groupedDivisions = [];
        $divisions = json_decode($this->getDivisions())->divisions;

        $years = $this->getYearRanges($sales);
        foreach($divisions as $division) {
            foreach ($years as $year) {
                $divisionSales = array_filter($sales, fn ($saleData) => $saleData['division'] === $division->division && $saleData['year'] === $year);
                $totalDivisionSale = array_reduce($divisionSales, function ($carry, $dSale) {
                    $carry += $dSale['amount'];
                    return $carry;
                });

                $groupedDivisions[$division->division][] = [
                    'division' => $division->division,
                    'amount' => number_format($totalDivisionSale, 2),
                ];
            }
        }

        return [
            'years' => array_values($years),
            'data' => array_values($groupedDivisions)
        ];
    }

    /**
     * @param array $sales
     * @return array
     */
    private function groupDivisionByMonth(array $sales): array
    {
        $groupedDivisions = [];
        $divisions = json_decode($this->getDivisions())->divisions;

        $years = $this->getYearRanges($sales);

        foreach($divisions as $division) {
            foreach ($years as $key => $year) {
                $divisionSales = array_filter($sales, fn ($saleData) => $saleData['division'] === $division->division && $saleData['year'] === $year);

                $month = '';
                $monthSales = [];
                $usedMonths = [];
                foreach ($divisionSales as $divisionSale) {
                    if ($month !== $divisionSale['month'] && !in_array($divisionSale['month'], $usedMonths)) {
                        $month = $divisionSale['month'];
                        $usedMonths[] = $month;

                        $divisionsInDate = array_filter($divisionSales, fn ($dSales) => $dSales['month'] === $month);
                        $monthTotalAmount = array_reduce($divisionsInDate, function ($carry, $monthSale) {
                            $carry += $monthSale['amount'];
                            return $carry;
                        });

                        $monthSales[] = [
                            'date' => $month . ' ' . $year,
                            'amount' => number_format($monthTotalAmount, 2)
                        ];
                    }
                }

                $groupedDivisions[$year][] = [
                    'division' => $division->division,
                    'months' => $monthSales,
                    'year' => $year
                ];
            }
        }

        return array_values($groupedDivisions);
    }

    /**
     * @param array $sales
     * @return array
     */
    private function getYearRanges(array $sales): array
    {
        $onlyYears = array_map(fn ($sale) => $sale['year'], $sales);
        sort($onlyYears);

        return [
            'start_year' => array_shift($onlyYears),
            'end_year' => array_pop($onlyYears),
        ];
    }

    /**
     * @param array $sales
     * @return array
     */
    private function getSanitizedData(array $sales): array
    {
        return array_map(fn ($sale) => [
            'id' => $sale['id'],
            'division' => $this->removeAccents($sale['division']),
            'year' => $sale['year'],
            'month' => $sale['month'],
            'customer_brand' => $this->removeAccents($sale['customer_brand']),
            'amount' => $sale['amount'],
            'created_at' => $sale['created_at'],
            'updated_at' => $sale['updated_at'],
        ], $sales);
    }

    /**
     * @param string $text
     * @return string
     */
    private function removeAccents(string $text): string
    {
        $trns = array(
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A',
            'ß'=>'B', 'ç'=>'c', 'Ç'=>'C',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e',
            'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
            'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I',
            'ñ'=>'n', 'Ñ'=>'N',
            'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o',
            'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O',
            'š'=>'s', 'Š'=>'S',
            'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U',
            'ý'=>'y', 'Ý'=>'Y', 'ž'=>'z', 'Ž'=>'Z'
        );

        return strtr($text, $trns);
    }
}