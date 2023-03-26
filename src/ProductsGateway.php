<?php

class ProductsGateway
{

    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getProducts ():array
    {
        $sql = 'SELECT product.id, product.sku, product.name,
                product.price, attribute.attributeName AS attribute,
                product.attributeValue AS value, attribute.unit FROM product JOIN attribute on attribute.id = product.attributeId
                ORDER BY id';

        $stmt = $this->conn->query($sql);

        return ($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function createProduct (object $data)
    {   
        
        $sql = 'INSERT INTO product (sku, name, price, attributeId, attributeValue)
                VALUES (:sku, :name, :price, :attributeId, :attributeValue)';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":sku", $data->sku, PDO::PARAM_STR);
        $stmt->bindValue(":name", $data->name, PDO::PARAM_STR);
        $stmt->bindValue(":price", $data->price, PDO::PARAM_INT);
        $stmt->bindValue(":attributeId", $data->attributeId, PDO::PARAM_INT);
        $stmt->bindValue(":attributeValue", $data->attributeValue, PDO::PARAM_STR);

        $stmt->execute();
        
        return ($this->conn->lastInsertId());
    }

    public function deleteProducts (array $ids)
    {   
        $sep_ids = implode(",",$ids);

        $sql = "DELETE FROM product WHERE id IN ($sep_ids)";

        $stmt = $this->conn->query($sql);

        return ($stmt->fetchAll(PDO::FETCH_ASSOC));

    }
}