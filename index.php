<?php
// Include files
include "Utils/Routing.php";

// Use classes;
use Utils\Routing;

$request = $_SERVER['REQUEST_URI'];
Routing::run($request);