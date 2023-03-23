<?php

    declare (strict_types=1);

    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

    header("Access-Control-Allow-Origin: *");

    header( 'Access-Control-Allow-Credentials: true' );

    header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS, PATCH");
    
    header("content-type: application/json; charset=UTF-8");

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header("HTTP/1.1 200 OK");
        die();
    }
    
    require "vendor/autoload.php";

    set_error_handler("ErrorHandler::handleError");

    set_exception_handler("ErrorHandler::handleException");

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();

    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    $parts = explode("/", $path);

    $resource = $parts[1];

    if($resource != "products") {

        http_response_code(404);
        exit;
    };

    $database = new Database ("db4free.net", "scandiwebtest", "pbelfort", "7591014pl");

    $products_gateway = new ProductsGateway($database);

    $controller = new ProductsController($products_gateway);

    $controller -> processRequest($_SERVER['REQUEST_METHOD']);