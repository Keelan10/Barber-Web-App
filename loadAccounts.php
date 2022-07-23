<?php
require_once("./includes/database.php");
$barberSQL = "SELECT * FROM barber";
$customerSQL = "SELECT * FROM customer";
$adminSQL = "SELECT * FROM admin";

$barberResult = $conn->query($barberSQL);
$customerResult = $conn->query($customerSQL);
$adminResult = $conn->query($adminSQL);

$accounts=array();

if (!($barberResult->rowCount() == 0 && $customerResult->rowCount() == 0 && $adminResult->rowCount() == 0)) {
    array_push($accounts,$barberResult->fetchAll(PDO::FETCH_ASSOC),$customerResult->fetchAll(PDO::FETCH_ASSOC),$adminResult->fetchAll(PDO::FETCH_ASSOC));
} 

header('Content-Type: application/json');
echo json_encode($accounts);
    
