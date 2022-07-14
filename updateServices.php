<?php
session_start();
require_once("includes/database.php");
if (isset($_POST['serviceID'])) {
    $serviceID = $_POST['serviceID'];
    $SQL = "Update service set name = " . $conn->quote($_POST['serviceName'])
        . ", duration = " . $conn->quote($_POST['serviceDura'])
        . ", price = " . $conn->quote($_POST['servicePrice'])
        . ", description = " . $conn->quote($_POST['serviceDesc'])
        . " WHERE serviceid = " . $conn->quote($_POST['serviceID']) . ";";
    $Result = $conn->query($SQL);
    $rowcount = $Result->rowCount();
    if ($Result) {
        echo "success";
    } else {
        echo "error";
    }
    // echo $SQL;
}