<?php
session_start();
require_once("includes/database.php");
if (isset($_POST['serviceID'])) {
    $serviceID = $_POST['serviceID'];
    $SQL = "DELETE FROM service where serviceid=" . $conn->quote($serviceID) . ";";
    $Result = $conn->exec($SQL);

    if (isset($Result) && $Result) {
        echo "success";
    } else {
        echo "failure";
    }
}