<?php
require_once("includes/database.php");

if (isset($_POST["orderId"])){
    $orderId=$_POST["orderId"];

    $SQL="SELECT product_name,orderdetails.quantity,(orderdetails.quantity*unit_price) AS totalPerProduct,DATE_FORMAT(paymentdate,'%M %d %Y') AS date , is_cancelled
        FROM orderdetails,product,transactions,payment
        WHERE orderid=".$orderId."
        AND orderdetails.productid=product.productid
        AND transactions.transactionid=orderdetails.orderid
        AND transactions.paymentid=payment.paymentid;";

    $Result= $conn->query($SQL);
    echo json_encode($Result->fetchAll());
}


