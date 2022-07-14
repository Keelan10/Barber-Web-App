<?php
session_start();
require_once("includes/database.php");
// $_POST["orderID"] = 2;
if (isset($_POST)) {
    $orderID = $_POST["orderID"];
    $innerSelect = "SELECT product_name,orderdetails.quantity,image_name
                    FROM orderdetails,product,transactions,payment
                    WHERE orderid= " .  $orderID . "
                    AND orderdetails.productid=product.productid
                    AND transactions.transactionid=orderdetails.orderid
                    AND transactions.paymentid=payment.paymentid;";
    $result1 = $conn->query($innerSelect);
    $arr_product = $result1->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($arr_product, JSON_PRETTY_PRINT);
}