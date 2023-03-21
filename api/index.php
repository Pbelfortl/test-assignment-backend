<?php

    declare (strict_types=1);
    
    require $_SERVER[__DIR__] . "../vendor/autoload.php";

    set_error_handler("ErrorHandler::handleError");

    set_exception_handler("ErrorHandler::handleException");

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));

    $dotenv->load();

    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    $parts = explode("/", $path);

    $resource = $parts[2];

    if($resource != "products") {

        http_response_code(404);
        exit;
    };

    header("content-type: application/json; charset:UTF-8");

    $database = new Database ($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

    $products_gateway = new ProductsGateway($database);

    $controller = new ProductsController($products_gateway);

    $controller -> processRequest($_SERVER['REQUEST_METHOD']);