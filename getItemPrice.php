<?php
require_once "includes/database.php";
$name= $_GET["name"];
//PERFORM SOME VALIDATION LOT LOT

$SQL= "SELECT price,quantity FROM product WHERE product_name=".$conn->quote($name).";";
$Result=$conn->query($SQL);

if ($Result->rowCount()==0){
    echo 0;
}
else{
    echo json_encode($Result->fetch());
}
