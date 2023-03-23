<?php

class ProductsController 
{   

    public function __construct(private ProductsGateway $gateway)
    {

    }

    public function processRequest (string $method) 
    {
        if($method == "GET") {

            echo json_encode($this->gateway->getProducts());

        } elseif ($method == "POST") {

            $data = json_decode(file_get_contents("php://input"));

            $errors = $this->validateData($data);

            if(!empty($errors)){
                $this->respondUnprocessableEntity($errors);
                return;
            }

            $id = $this->gateway->createProduct($data);

            $this->respondCreated($id);

        } elseif ($method == "PATCH") {

            $data = json_decode(file_get_contents("php://input"));

            $rows = $this->gateway->deleteProducts($data);

            return $rows;

        } else {
            
            $this->respondMethodNotAllowed("GET, POST, DELETE");
        }
    }

    private function respondUnprocessableEntity (array $errors) :void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function respondMethodNotAllowed (string $allowedMethods) :void
    {
        http_response_code(405);
        header("Allow:$allowedMethods");
    }

    private function respondCreated (string $id) :void
    {
        http_response_code(201);
        echo json_encode(["message" => "Product added", "id" => $id ]);
    }

    private function validateData (object $data) :array
    {
        $errors = [];

        if(filter_var($data->price, FILTER_VALIDATE_INT) === false){

            $errors[] = "price must be an integer";
        }

        if(filter_var($data->attributeId, FILTER_VALIDATE_INT) === false){

            $errors[] = "attributeId must be an integer";
        }

        return $errors;
    }
};