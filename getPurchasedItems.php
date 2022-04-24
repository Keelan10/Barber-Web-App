<?php
require_once("includes/database.php");
//sanitize lotlot
// $orderId=1;
$orderId=$_GET["orderId"];

$SQL="SELECT product_name,orderdetails.quantity,(orderdetails.quantity*price) AS totalPerProduct,DATE_FORMAT(paymentdate,'%M %d %Y') AS date FROM orderdetails,product,transactions,payment
    WHERE orderid=".$orderId."
    AND orderdetails.productid=product.productid
    AND transactions.transactionid=orderdetails.orderid
    AND transactions.paymentid=payment.paymentid;";

$Result= $conn->query($SQL);
echo json_encode($Result->fetchAll());



