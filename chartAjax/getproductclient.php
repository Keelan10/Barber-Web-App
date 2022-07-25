<?php

use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    ValidationError,
    Schema
};

require '../vendor/autoload.php';
require_once("../includes/database.php");

$sql = "SELECT product.product_name,product.productid,COALESCE(SUM(orderdetails.quantity),0) AS Quantity
        FROM product
        LEFT JOIN orderdetails
        on product.productid = orderdetails.productid
        GROUP BY product.productid;";

$result = $conn->query($sql);
$array_result = $result->fetchAll(PDO::FETCH_ASSOC);

$data = json_encode($array_result, JSON_NUMERIC_CHECK);
$data1 = json_decode($data);
$schema = Schema::fromJsonString(file_get_contents('../schema/adminJSONSchema.json'));

$validator = new Validator();

/** @var ValidationResult $result */
$result = $validator->schemaValidation($data1, $schema);

if ($result->isValid()) {
    //echo '$data is valid', PHP_EOL;
    header('Content-Type: application/json');
    echo $data;
} else {
    /** @var ValidationError $error */
    $error = $result->getFirstError();
    echo '$data is invalid', PHP_EOL;
    echo "Error: ", $error->keyword(), PHP_EOL;
    echo json_encode($error->keywordArgs(), JSON_PRETTY_PRINT), PHP_EOL;
}
