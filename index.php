<?php

    declare (strict_types=1);

    header("Access-Control-Allow-Headers: Authorization, Content-Type");
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
    header('content-type: application/json; charset=utf-8');
    
    require "vendor/autoload.php";

    set_error_handler("ErrorHandler::handleError");
    set_exception_handler("ErrorHandler::handleException");

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $database = new Database ($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

    $products_gateway = new ProductsGateway($database);

    $controller = new ProductsController($products_gateway);

    $controller -> processRequest($_SERVER["REQUEST_METHOD"]);