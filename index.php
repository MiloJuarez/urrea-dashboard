<?php
// Include files
include_once "Utils/Routing.php";
include_once "Utils/DotEnv.php";

// Use classes;
use Utils\Routing;
use Utils\DotEnv;

(new DotEnv(__DIR__ . '/.env'))->load();

$request = $_SERVER['REQUEST_URI'];
Routing::run($request);