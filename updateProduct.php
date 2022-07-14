<?php
require_once("includes/database.php");
session_start();
if (isset($_POST["productID"])) {
    $sUpdate = "Update product set product_name = '" . $_POST["product_name"] . "' ,price = " . $_POST["price"] . " where productid = '" . $_POST["productID"] . "';";
    //echo $sUpdate;
    $Result = $conn->query($sUpdate);
    $rowcount = $Result->rowCount();
    if ($Result) {
        echo "success";
    } else {
        echo "error";
    }
}