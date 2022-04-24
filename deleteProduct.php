<?php
require_once("includes/database.php");
//sanitize lot lot
$productName= $_GET["productName"];

$SQL= "DELETE FROM product where product_name=".$conn->quote($productName).";";
$Result=$conn->exec($SQL);

if(isset($Result) && $Result){
    echo "success";
}
else{
    echo "failure";
}