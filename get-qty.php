<?php
require_once ("includes/database.php");
$name=$_GET["q"];
//TO DO: sanitize $name sipakieter 

$SQLstatement="SELECT quantity FROM product WHERE product_name=".$conn->quote($name).";";
$result=$conn->query($SQLstatement);

$qty=($result->fetch())["quantity"];
echo $qty;

?>