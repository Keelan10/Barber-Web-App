<?php
session_start();
require_once("includes/database.php");
if (isset($_POST)) {
    $orderID = $_POST["orderID"];
    $update  = "UPDATE orders SET picked = 1 WHERE transactionid = " . $orderID . ";";
    $result = $conn->exec($update);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}