<?php

require_once __DIR__ . "/../vendor/autoload.php";

use IRFANM\SIASHAF\App\Router;
use IRFANM\SIASHAF\Controller\TestController;

Router::route("GET", "/test-route", TestController::class, "index", []);

Router::gas();