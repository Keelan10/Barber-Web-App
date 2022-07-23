<?php
require_once("./includes/database.php");

if(isset($_POST["barberid"])){

    $preparedStatement = $conn->prepare("SELECT day,start_time FROM schedules,scheduledays WHERE barberid=? AND schedules.scheduleid=scheduledays.scheduleid");
    $preparedStatement->execute(array($_POST["barberid"]));

    echo json_encode($preparedStatement->fetchAll());
}