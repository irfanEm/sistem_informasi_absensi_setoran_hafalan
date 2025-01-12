<?php

require_once __DIR__ . "/../vendor/autoload.php";

use IRFANM\SIASHAF\App\Router;
use IRFANM\SIASHAF\Controller\HomeController;
use IRFANM\SIASHAF\Controller\TestController;
use IRFANM\SIASHAF\Controller\UserController;

Router::route("GET", "/", HomeController::class, "index", []);
Router::route("GET", "/test-route", TestController::class, "index", []);
Router::route("GET", "/test-env", TestController::class, "testDotEnvLibrary", []);
Router::route("GET", "/test_db", TestController::class, "testConnDb", []);

Router::route("GET", "/users", UserController::class, "index", []);

// User Route
Router::route("GET", "/users/register", UserController::class, "register", []);
Router::route("GET", "/users/login", UserController::class, "login", []);
Router::route("POST", "/users/login", UserController::class, "postLogin", []);

Router::gas();