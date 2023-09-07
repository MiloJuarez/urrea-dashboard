<?php

namespace Utils;

include "Controllers/DashboardController.php";
include "Controllers/SalesController.php";

use Controllers\DashboardController;
use Controllers\SalesController;

class Routing
{
    public static function run($requestUri): void
    {
        $basePath = '/urrea-dashboard/';

        if ($basePath === $requestUri) {
            $ctrlDashboard = new DashboardController();
            $ctrlDashboard->index();
        } else if (str_contains($requestUri, $basePath . 'sales')) {
            $salesCtrl = new SalesController();
            echo json_encode($salesCtrl->index());
        } else if (str_contains($requestUri, $basePath . 'customers')) {
            $salesCtrl = new SalesController();
            echo json_encode($salesCtrl->getCustomers());
        } else if (str_contains($requestUri, $basePath . 'divisions')) {
            $salesCtrl = new SalesController();
            echo json_encode($salesCtrl->getDivisions());
        } else {
            header('HTTP/1.0 404 Not Found');
            call_user_func_array(function () {}, [$requestUri]);
        }

    }
}