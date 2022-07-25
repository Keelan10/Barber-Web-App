<?php
session_start();
require_once("../includes/database.php");
if (isset($_POST)) {
    $sql = "SELECT concat(barber.first_name,\" \",barber.last_name) as barbername,barber.barberid,COUNT(appointment.barberid) as appointments
            FROM barber
            LEFT JOIN appointment
            ON barber.barberid = appointment.barberid
            GROUP BY barber.barberid;";

    $result = $conn->query($sql);
    $array_result = $result->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);
}